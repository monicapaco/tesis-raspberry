#!/bin/bash

BRANCH=$1
PROJECT_DIR="/var/www/app"
LOG_FILE="/var/log/deploy.log"
TIMESTAMP=$(date '+%Y-%m-%d %H:%M:%S')

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
# 3. Verificar si hay cambios
# ──────────────────────────────────────────
LOCAL_COMMIT=$(git rev-parse HEAD)
REMOTE_COMMIT=$(git rev-parse origin/$BRANCH)

if [ "$LOCAL_COMMIT" = "$REMOTE_COMMIT" ]; then
  echo "✅ Todo está actualizado, no hay cambios nuevos"
  exit 0
fi

echo "📦 Cambios detectados:"
echo "   Local:  $LOCAL_COMMIT"
echo "   Remoto: $REMOTE_COMMIT"
echo ""
echo "📋 Commits que se van a aplicar:"
git log --oneline HEAD..origin/$BRANCH

# ──────────────────────────────────────────
# 4. Backup de base de datos (antes de tocar nada)
# ──────────────────────────────────────────
echo ""
echo "💾 Haciendo backup de la base de datos..."
BACKUP_DIR="/var/backups/db"
mkdir -p $BACKUP_DIR

# Lee las credenciales desde el .env de Laravel
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
# 6. Pull
# ──────────────────────────────────────────
echo "📥 Descargando cambios..."
git pull origin $BRANCH

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
  npm ci                  # más seguro que npm install en prod
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
# 11. Permisos
# ──────────────────────────────────────────
echo "🔐 Ajustando permisos..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# ──────────────────────────────────────────
# 12. Levantar sitio
# ──────────────────────────────────────────
echo "🟢 Desactivando mantenimiento..."
php artisan up

# ──────────────────────────────────────────
# Log de éxito
# ──────────────────────────────────────────
echo "[$TIMESTAMP] DEPLOY OK - rama: $BRANCH - commit: $REMOTE_COMMIT" >> $LOG_FILE

echo ""
echo "✅ ¡Deploy completado correctamente! 🎉"
echo "   Rama:   $BRANCH"
echo "   Commit: $REMOTE_COMMIT"
