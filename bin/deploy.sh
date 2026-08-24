#!/usr/bin/env bash
#
# Deploy-script — draait OP DE SERVER, in de root van de site.
# Gebruik:  bin/deploy.sh            (deployt de laatste main)
#           bin/deploy.sh <branch>   (deployt een andere branch)
#
set -euo pipefail

BRANCH="${1:-main}"
APP_DIR="$(cd "$(dirname "$0")/.." && pwd)"
cd "$APP_DIR"

echo "==> Deploy van branch '$BRANCH' in $APP_DIR"

echo "==> Code ophalen"
git fetch origin
git checkout "$BRANCH"
git reset --hard "origin/$BRANCH"

echo "==> Composer dependencies (zonder dev-packages)"
composer install --no-dev --no-interaction --optimize-autoloader

echo "==> Database-migraties"
bin/cake migrations migrate --no-lock

echo "==> Cache legen"
bin/cake cache clear_all

echo "==> Klaar. Gedeployde commit:"
git log -1 --oneline
