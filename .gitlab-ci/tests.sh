#!/bin/bash

# Install dependencies only for Docker.
if ! [ -f /.dockerenv ] ; then
  echo "Not running inside docker, exiting to avoid data damage." >&2
  exit 1
fi

set -xe

# Install Composer and project dependencies.
composer install --no-interaction

# Altera as configurações para o modo de teste
cp .env.example .env
sed -i 's/^APP_ENV=.*$/APP_ENV=testing/g' .env
sed -i 's/^DB_HOST=.*$/DB_HOST=mysql/g' .env
sed -i 's/^DB_USERNAME=.*$/DB_USERNAME=root/g' .env
sed -i 's/^CACHE_DRIVER=.*$/CACHE_DRIVER=array/g' .env
sed -i 's/^SESSION_DRIVER=.*$/SESSION_DRIVER=array/g' .env
sed -i 's/^MAIL_DRIVER=.*$/MAIL_DRIVER=log/g' .env

# Generate an application key. Re-cache.
php artisan key:generate
php artisan config:cache

# Run database migrations and seeders
php artisan migrate:refresh --seed

# Executa os testes do PHPUnit
./vendor/bin/phpunit --colors --debug
