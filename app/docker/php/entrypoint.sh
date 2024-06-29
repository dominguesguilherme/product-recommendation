#!/usr/bin/env ash
set -eu

# Installing dependences
echo "- Installing composer dependences"
composer install \
    --optimize-autoloader \
    --no-ansi \
    --no-interaction \
    --no-progress
echo "- Done"

exec "$@"