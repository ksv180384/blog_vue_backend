#!/bin/sh
# Alpine shell is `sh`
# Provision the environment in here!

echo "Starting app entrypoint..."

set -e

cd /var/www/blog

cp .env.example.docker .env

composer install

echo "Ensuring laravel logs directory existence and permissions..."
mkdir -p /storage/logs
mkdir -p /storage/app/public
mkdir -p /storage/framework/cache
mkdir -p /storage/framework/sessions
mkdir -p /storage/framework/testing
mkdir -p /storage/framework/views
chown -R 1000:1000 /storage
chmod -R 0777 /storage

echo "Running artisan commands to get the app provisioned..."
php artisan key:generate
php artisan storage:link

php artisan cache:clear
php artisan config:clear
php artisan view:clear

php artisan migrate:fresh --seed
php artisan jwt:secret

echo "Completed app entrypoint!"

echo "Running container commands..."
exec "$@"
