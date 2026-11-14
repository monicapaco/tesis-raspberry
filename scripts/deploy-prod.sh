#!/bin/bash

BRANCH=$1
PROJECT_DIR="/var/www/html"
LOG_FILE="${HOME}/.local/state/tesis-raspberry/deploy.log"
TIMESTAMP=$(date '+%Y-%m-%d %H:%M:%S')
MAINTENANCE_ENABLED=0
BACKUP_TEMP_FILE=""

log_event() {
  local message="$1"
  local log_dir
  log_dir=$(dirname "$LOG_FILE")

  if ! mkdir -p "$log_dir" 2>/dev/null || ! printf '%s\n' "$message" >> "$LOG_FILE"; then
    echo "⚠️  No se pudo guardar el log en $LOG_FILE" >&2
  fi
}

# ──────────────────────────────────────────
# Cargar fnm con ruta absoluta
# ──────────────────────────────────────────
export FNM_DIR="/home/pi/.local/share/fnm"
export PATH="$FNM_DIR:$PATH"
eval "$($FNM_DIR/fnm env --shell bash 2>/dev/null)" || true

# ──────────────────────────────────────────
# Función: asegurar que el sitio vuelva a subir
# aunque el script falle en cualquier punto
# ──────────────────────────────────────────
cleanup() {
  local exit_code=$?
  trap - EXIT

  if [ -n "$BACKUP_TEMP_FILE" ] && [ -f "$BACKUP_TEMP_FILE" ]; then
    rm -f -- "$BACKUP_TEMP_FILE"
  fi

  if [ "$exit_code" -ne 0 ]; then
    echo ""
    echo "❌ El deploy falló."
    if [ "$MAINTENANCE_ENABLED" -eq 1 ]; then
      echo "Intentando restaurar el sitio..."
      php artisan up 2>/dev/null || true
    fi
    echo "⚠️  Revisa los errores arriba antes de volver a intentar."
    log_event "[$TIMESTAMP] DEPLOY FALLIDO - rama: $BRANCH"
  fi

  exit "$exit_code"
}
trap cleanup EXIT

set -e

DEPLOY_COMMIT="${DEPLOY_BOOTSTRAP_COMMIT:-}"
if [ "${DEPLOY_BOOTSTRAPPED:-}" != "1" ] || [ -z "$DEPLOY_COMMIT" ]; then
  echo "❌ Ejecuta el deploy mediante ./scripts/deploy-bootstrap.sh <rama> para usar siempre la versión remota validada."
  exit 1
fi

# Cargar fnm
export PATH="/usr/local/bin:/usr/bin:/bin"
export FNM_DIR="$HOME/.local/share/fnm"
[ -s "$FNM_DIR/fnm" ] && export PATH="$FNM_DIR:$PATH"
eval "$(fnm env 2>/dev/null)" || true

echo "🚀 Iniciando deploy... [$TIMESTAMP]"

# ──────────────────────────────────────────
# 1. Validar parámetro
# ──────────────────────────────────────────
if [ -z "$BRANCH" ]; then
  echo "❌ Debes indicar una rama. Ejemplo: ./deploy.sh main"
  exit 1
fi

cd "$PROJECT_DIR" || { echo "❌ No se encontró el proyecto en $PROJECT_DIR"; exit 1; }

CURRENT_BRANCH=$(git branch --show-current)
if [ "$CURRENT_BRANCH" != "$BRANCH" ]; then
  echo "❌ El checkout está en '$CURRENT_BRANCH', pero se solicitó desplegar '$BRANCH'. Cambia a esa rama y vuelve a ejecutar el bootstrap."
  exit 1
fi

if ! git diff --quiet HEAD --; then
  echo "❌ Hay cambios locales en archivos versionados. Guárdalos o descártalos antes del deploy."
  exit 1
fi

if ! git cat-file -e "$DEPLOY_COMMIT^{commit}" 2>/dev/null || ! git merge-base --is-ancestor HEAD "$DEPLOY_COMMIT"; then
  echo "❌ El commit remoto validado no avanza limpiamente desde el checkout actual. No se actualizará el código."
  exit 1
fi

DEPLOY_USER=$(id -un)
if [ "$EUID" -eq 0 ]; then
  echo "❌ Ejecuta el deploy como el usuario pi, no como root. El script solicitará sudo solo para los permisos necesarios."
  exit 1
fi

ACTIVE_GROUPS=" $(id -nG) "
if [[ "$ACTIVE_GROUPS" != *" www-data "* ]]; then
  ACCOUNT_GROUPS=" $(id -nG "$DEPLOY_USER") "
  if [[ "$ACCOUNT_GROUPS" == *" www-data "* ]]; then
    echo "❌ El usuario $DEPLOY_USER ya pertenece a www-data, pero esta sesión aún no tiene el grupo activo. Cierra la sesión SSH y vuelve a entrar."
    exit 1
  fi

  echo "⚠️  El usuario $DEPLOY_USER necesita pertenecer al grupo www-data para que el deploy y PHP compartan storage y cache."
  if [ ! -t 0 ]; then
    echo "❌ Se necesita una terminal interactiva para confirmar el cambio de grupo."
    exit 1
  fi

  read -r -p "¿Deseas permitir que sudo agregue $DEPLOY_USER al grupo www-data? [s/N] " CONFIRM_GROUP
  CONFIRM_GROUP="${CONFIRM_GROUP,,}"
  case "$CONFIRM_GROUP" in
    s|si|sí)
      if ! command -v sudo >/dev/null 2>&1; then
        echo "❌ No se encontró sudo para agregar el usuario al grupo."
        exit 1
      fi
      sudo usermod -aG www-data "$DEPLOY_USER"
      echo "✅ Usuario agregado. Cierra la sesión SSH y vuelve a entrar; luego ejecuta nuevamente el bootstrap."
      exit 1
      ;;
    *)
      echo "❌ No se cambió el grupo. Deploy cancelado."
      exit 1
      ;;
  esac
fi

if ! command -v sudo >/dev/null 2>&1; then
  echo "❌ Se necesita sudo para ajustar storage y bootstrap/cache."
  exit 1
fi

echo "🔐 Se requiere permiso para ajustar storage y bootstrap/cache."
if [ ! -t 0 ]; then
  echo "❌ Se necesita una terminal interactiva para confirmar el ajuste de permisos."
  exit 1
fi

read -r -p "¿Deseas permitir que sudo ajuste esos directorios para www-data? [s/N] " CONFIRM_PERMISSIONS
CONFIRM_PERMISSIONS="${CONFIRM_PERMISSIONS,,}"
case "$CONFIRM_PERMISSIONS" in
  s|si|sí) ;;
  *)
    echo "❌ No se aceptó el ajuste de permisos. Deploy cancelado antes de actualizar el código."
    exit 1
    ;;
esac

# ──────────────────────────────────────────
# 2. Verificar rama y versión remota
# ──────────────────────────────────────────
echo "🔎 Rama '$BRANCH' validada en el commit $DEPLOY_COMMIT..."

# ──────────────────────────────────────────
# 3. Backup de base de datos (antes de actualizar el código)
# ──────────────────────────────────────────
echo ""
echo "💾 Haciendo backup de la base de datos..."
BACKUP_DIR="/var/backups/db"

if ! mkdir -p "$BACKUP_DIR" 2>/dev/null || [ ! -w "$BACKUP_DIR" ]; then
  echo "⚠️  No hay permiso de escritura en $BACKUP_DIR."

  if [ ! -t 0 ]; then
    echo "❌ Se necesita una terminal interactiva para confirmar la corrección de permisos."
    exit 1
  fi

  read -r -p "¿Deseas permitir que sudo ajuste los permisos de esta carpeta? [s/N] " CONFIRM_FIX
  CONFIRM_FIX="${CONFIRM_FIX,,}"
  case "$CONFIRM_FIX" in
    s|si|sí)
      if [ "$EUID" -eq 0 ]; then
        install -d -o "$(id -un)" -g "$(id -gn)" -m 0700 "$BACKUP_DIR"
      elif command -v sudo >/dev/null 2>&1; then
        sudo install -d -o "$(id -un)" -g "$(id -gn)" -m 0700 "$BACKUP_DIR"
      else
        echo "❌ No se encontró sudo para corregir los permisos."
        exit 1
      fi
      ;;
    *)
      echo "❌ No se corrigieron los permisos. Deploy cancelado antes de actualizar el código."
      exit 1
      ;;
  esac
fi

if [ ! -w "$BACKUP_DIR" ]; then
  echo "❌ Sigue sin haber permiso de escritura en $BACKUP_DIR. Deploy cancelado."
  exit 1
fi

DB_DATABASE=$(grep '^DB_DATABASE=' .env | cut -d '=' -f2)
DB_USERNAME=$(grep '^DB_USERNAME=' .env | cut -d '=' -f2)
DB_PASSWORD=$(grep '^DB_PASSWORD=' .env | cut -d '=' -f2)

BACKUP_FILE="$BACKUP_DIR/backup_$(date '+%Y%m%d_%H%M%S').sql"
BACKUP_TEMP_FILE=$(mktemp "$BACKUP_DIR/.backup_XXXXXX.tmp")
if ! mysqldump -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" > "$BACKUP_TEMP_FILE"; then
  echo "❌ No se pudo generar el backup de la base de datos."
  exit 1
fi
mv -- "$BACKUP_TEMP_FILE" "$BACKUP_FILE"
BACKUP_TEMP_FILE=""
echo "✅ Backup guardado en: $BACKUP_FILE"

# Mantener solo los últimos 7 backups
ls -t $BACKUP_DIR/backup_*.sql | tail -n +8 | xargs rm -f 2>/dev/null || true

# ──────────────────────────────────────────
# 4. Preparar permisos antes de actualizar el código
# ──────────────────────────────────────────
echo "🔐 Ajustando permisos compartidos..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo find storage bootstrap/cache -type d -exec chmod 2770 {} +
sudo find storage bootstrap/cache -type f -exec chmod 0660 {} +

# ──────────────────────────────────────────
# 5. Pull
# ──────────────────────────────────────────
echo "📥 Descargando cambios..."
git merge --ff-only "$DEPLOY_COMMIT"

# ──────────────────────────────────────────
# 6. Activar mantenimiento
# ──────────────────────────────────────────
echo "🛠️  Activando modo mantenimiento..."
php artisan down --retry=60
MAINTENANCE_ENABLED=1

# ──────────────────────────────────────────
# 7. Composer
# ──────────────────────────────────────────
echo "📦 Instalando dependencias PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

# ──────────────────────────────────────────
# 8. NPM + Build
# ──────────────────────────────────────────
if [ -f package.json ]; then
  echo "📦 Instalando dependencias JS..."
  npm ci
  echo "⚡ Compilando frontend..."
  npm run build
else
  echo "ℹ️  Sin frontend (no hay package.json)"
fi

# ──────────────────────────────────────────
# 9. Migraciones
# ──────────────────────────────────────────
echo "🗄️  Ejecutando migraciones..."
php artisan migrate --force

# ──────────────────────────────────────────
# 10. Cache
# ──────────────────────────────────────────
echo "⚙️  Actualizando caché..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ──────────────────────────────────────────
# 11. Levantar sitio
# ──────────────────────────────────────────
echo "🟢 Desactivando mantenimiento..."
php artisan up

# ──────────────────────────────────────────
# Log de éxito
# ──────────────────────────────────────────
CURRENT_COMMIT=$(git rev-parse HEAD)
log_event "[$TIMESTAMP] DEPLOY OK - rama: $BRANCH - commit: $CURRENT_COMMIT"

echo ""
echo "✅ ¡Deploy completado correctamente! 🎉"
echo "   Rama:   $BRANCH"
echo "   Commit: $CURRENT_COMMIT"
