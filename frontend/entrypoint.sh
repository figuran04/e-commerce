#!/bin/sh
set -eu

printf 'Waiting for database...\n'
until php -r 'new PDO("mysql:host=" . getenv("DB_HOST") . ";port=" . (getenv("DB_PORT") ?: "3306") . ";charset=utf8mb4", getenv("DB_ROOT_USER"), getenv("DB_ROOT_PASSWORD"));' >/dev/null 2>&1; do
  sleep 2
done

printf 'Running database setup...\n'
php /var/www/html/scratch/setup_databases_phase3.php

printf 'Starting Apache...\n'
exec apache2-foreground
