#!/bin/bash

set -Eeo pipefail

# Copy .env.example to .env if it is not present
if [ ! -f ".env" ]; then
    cp .env.example .env
    echo "Copied .env.example to .env"
fi

# Install composer and run 'composer install'
docker exec -it sourcetoad_aihack_code composer install

# Build Databases
# TODO replace w/ pgsql
docker exec -i sourcetoad_mariadb1011 mysql -uroot -proot --execute="CREATE DATABASE IF NOT EXISTS aihack"
docker exec -i sourcetoad_mariadb1011 mysql -uroot -proot --execute="CREATE DATABASE IF NOT EXISTS aihack_test"

# Setup assets
docker exec -it sourcetoad_aihack_code npm install
docker exec -it sourcetoad_aihack_code npm run build

# Execute commands to setup laravel
docker exec -it sourcetoad_aihack_code php artisan key:generate
docker exec -it sourcetoad_aihack_code php artisan migrate
