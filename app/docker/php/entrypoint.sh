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

# If running via docker-compose, then it is necessary to wait for the mysql container to be available
echo "- Waiting for mysql in host '$DB_SERVER'"
until mysql -u $DB_USER -p$DB_PASSWORD -h $DB_SERVER --port=$DB_PORT -e "exit" >&2
do
    echo "-- MySQL is unavailable - sleeping"
    sleep 1
done
echo "- Done! MySQL is up - executing command"

echo "- Running Migrations"
/app/bin/console doctrine:migrations:migrate --no-interaction
echo "- Done"

echo "- Populating Products"
php /app/bin/console doctrine:query:sql "INSERT IGNORE INTO products (id, sku, name) VALUES ('1dcab66f-362c-49e4-a84c-6a2c1e1ea9a2', 'CHUTEIRA01', 'Chuteira'), ('f245a8c8-0f5e-4e77-ae4d-79a99f52db41', 'MEIA01', 'Meia'), ('80e27365-42f0-46cf-ae3d-cc24aeeb70b1', 'BOLA01', 'Bola'), ('be4d3de7-4a5f-4b8d-9d4a-55799d6a57c8', 'CAMISA01', 'Camisa'), ('4cbf8c57-8d87-4aeb-9c0d-cde19b9e7b05', 'CALCA01', 'Calça'), ('ee8e53b5-8f79-419b-83a0-3f6f0dddcf35', 'LUVA01', 'Luva'), ('ea0d2a9a-bc3b-4fa4-b96e-9f1e1e512c1b', 'BONÉ01', 'Boné'), ('1d2ab2f5-6bb2-41e2-a5ec-3f7819fdd6d1', 'BOTA01', 'Bota'),  ('a4d0d2e5-06e8-4568-8c95-ec7d400e61a6', 'SHORTS01', 'Shorts'), ('6c9c8e07-450b-4785-b63c-85a1636e4f32', 'JAQUETA01', 'Jaqueta');"
echo "- Done"


exec "$@"