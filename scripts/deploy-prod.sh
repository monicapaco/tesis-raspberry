#!/bin/bash

BRANCH=$1
PROJECT_DIR="/var/www/html"
LOG_FILE="/var/log/deploy.log"
TIMESTAMP=$(date '+%Y-%m-%d %H:%M:%S')

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
  if [ $? -ne 0 ]; then
    echo ""
    echo "❌ El deploy falló. Intentando restaurar el sitio..."
    php artisan up 2>/dev/null || true
    echo "⚠️  Revisa los errores arriba antes de volver a intentar."
    echo "[$TIMESTAMP] DEPLOY FALLIDO - rama: $BRANCH" >> $LOG_FILE
  fi
}
trap cleanup EXIT

set -e

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

cd $PROJECT_DIR || { echo "❌ No se encontró el proyecto en $PROJECT_DIR"; exit 1; }

# ──────────────────────────────────────────
# 2. Verificar rama remota
# ──────────────────────────────────────────
echo "🔎 Verificando rama '$BRANCH'..."
git fetch origin

if ! git show-ref --verify --quiet refs/remotes/origin/$BRANCH; then
  echo "❌ La rama '$BRANCH' no existe en el repositorio remoto"
  exit 1
fi

# ──────────────────────────────────────────
# 3. Pull
# ──────────────────────────────────────────
echo "📥 Descargando cambios..."
git pull origin $BRANCH

# ──────────────────────────────────────────
# 4. Backup de base de datos (antes de tocar nada)
# ──────────────────────────────────────────
echo ""
echo "💾 Haciendo backup de la base de datos..."
BACKUP_DIR="/var/backups/db"
mkdir -p $BACKUP_DIR

DB_DATABASE=$(grep '^DB_DATABASE=' .env | cut -d '=' -f2)
DB_USERNAME=$(grep '^DB_USERNAME=' .env | cut -d '=' -f2)
DB_PASSWORD=$(grep '^DB_PASSWORD=' .env | cut -d '=' -f2)

BACKUP_FILE="$BACKUP_DIR/backup_$(date '+%Y%m%d_%H%M%S').sql"
mysqldump -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" > "$BACKUP_FILE"
echo "✅ Backup guardado en: $BACKUP_FILE"

# Mantener solo los últimos 7 backups
ls -t $BACKUP_DIR/backup_*.sql | tail -n +8 | xargs rm -f 2>/dev/null || true

# ──────────────────────────────────────────
# 5. Activar mantenimiento
# ──────────────────────────────────────────
echo "🛠️  Activando modo mantenimiento..."
php artisan down --retry=60 || true

# ──────────────────────────────────────────
# 6. Composer
# ──────────────────────────────────────────
echo "📦 Instalando dependencias PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

# ──────────────────────────────────────────
# 7. NPM + Build
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
# 8. Migraciones
# ──────────────────────────────────────────
echo "🗄️  Ejecutando migraciones..."
php artisan migrate --force

# ──────────────────────────────────────────
# 9. Cache
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
# 10. Permisos
# ──────────────────────────────────────────
echo "🔐 Ajustando permisos..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# ──────────────────────────────────────────
# 11. Levantar sitio
# ──────────────────────────────────────────
echo "🟢 Desactivando mantenimiento..."
php artisan up

# ──────────────────────────────────────────
# Log de éxito
# ──────────────────────────────────────────
CURRENT_COMMIT=$(git rev-parse HEAD)
echo "[$TIMESTAMP] DEPLOY OK - rama: $BRANCH - commit: $CURRENT_COMMIT" >> $LOG_FILE

echo ""
echo "✅ ¡Deploy completado correctamente! 🎉"
echo "   Rama:   $BRANCH"
echo "   Commit: $CURRENT_COMMIT"
