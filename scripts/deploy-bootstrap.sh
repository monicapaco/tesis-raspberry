#!/usr/bin/env bash

set -Eeuo pipefail

BRANCH="${1:-}"
PROJECT_DIR="/var/www/html"

if [ -z "$BRANCH" ]; then
  echo "Uso: ./scripts/deploy-bootstrap.sh <rama>"
  exit 1
fi

if ! git check-ref-format --branch "$BRANCH" >/dev/null 2>&1; then
  echo "❌ Nombre de rama inválido: $BRANCH"
  exit 1
fi

cd "$PROJECT_DIR" || {
  echo "❌ No se encontró el proyecto en $PROJECT_DIR"
  exit 1
}

if ! git rev-parse --is-inside-work-tree >/dev/null 2>&1; then
  echo "❌ $PROJECT_DIR no es un repositorio Git."
  exit 1
fi

REMOTE_REF="refs/remotes/origin/$BRANCH"
echo "🔄 Descargando la referencia origin/$BRANCH..."
git fetch origin "+$BRANCH:$REMOTE_REF"

if ! git show-ref --verify --quiet "$REMOTE_REF"; then
  echo "❌ La rama '$BRANCH' no existe en el repositorio remoto."
  exit 1
fi

DEPLOY_SCRIPT=$(mktemp "${TMPDIR:-/tmp}/deploy-prod.XXXXXX.sh")
cleanup() {
  rm -f -- "$DEPLOY_SCRIPT"
}
trap cleanup EXIT

DEPLOY_COMMIT=$(git rev-parse "$REMOTE_REF^{commit}")
if ! git show "$DEPLOY_COMMIT:scripts/deploy-prod.sh" > "$DEPLOY_SCRIPT"; then
  echo "❌ No se pudo obtener scripts/deploy-prod.sh desde origin/$BRANCH."
  exit 1
fi

if ! bash -n "$DEPLOY_SCRIPT"; then
  echo "❌ El deploy remoto tiene errores de sintaxis. No se iniciará el despliegue."
  exit 1
fi

echo "✅ Deploy remoto validado; iniciando el commit $DEPLOY_COMMIT."
DEPLOY_BOOTSTRAPPED=1 DEPLOY_BOOTSTRAP_COMMIT="$DEPLOY_COMMIT" bash "$DEPLOY_SCRIPT" "$BRANCH"
