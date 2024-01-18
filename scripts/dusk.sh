#!/usr/bin/env bash

set -Eeo pipefail

if ! [ -x "$(command -v docker)" ]; then
  echo "Error: docker is not installed or you are running this inside the container."
  exit 1
fi

CODE_CONTAINER_NAME="sourcetoad_aihack_code"
DUSK_CONTAINER_NAME="sourcetoad_aihack_dusk"
DUSK_CONTAINER_IMAGE="seleniarm/standalone-chromium:latest"

if [ "$(uname -m)" != "arm64" ]; then
    DUSK_CONTAINER_IMAGE="selenium/standalone-chrome:latest"
fi

clean_up () {
    ARG=$?
    echo "Cleaning up"

    if [ -n "$(docker container ls | grep "$DUSK_CONTAINER_NAME")" ]; then
        docker stop "$DUSK_CONTAINER_NAME" >/dev/null
    fi

    if [ -n "$(docker container ls -a | grep "$DUSK_CONTAINER_NAME")" ]; then
        docker rm "$DUSK_CONTAINER_NAME" >/dev/null
    fi

    if [ -f ".env.dusk.local" ]; then
        rm ".env.dusk.local";
    fi

    if [ -f ".env.dusk.local~" ]; then
        rm ".env.dusk.local~";
    fi

    exit $ARG
}

trap clean_up EXIT

echo "Setting up Dusk environment"
cp .env .env.dusk.local
sed -i~ 's/^DB_DATABASE=aihack$/DB_DATABASE=aihack_test/g' .env.dusk.local
sed -i~ 's/^MAIL_MAILER=.*/MAIL_MAILER=log/g' .env.dusk.local

if [ ! -f public/hot ] && [ ! -f public/build/manifest.json ]; then
    echo "No asset build detected. Build the assets or start the dev server before running this script."
    exit 1
fi

if [ ! -f "vendor/laravel/dusk/bin/chromedriver-linux" ]; then
    echo "Did not find chromedriver-linux, installing via php artisan dusk:chrome-driver"
    docker exec -it "$CODE_CONTAINER_NAME" php artisan dusk:chrome-driver
fi

echo "Starting Selenium Container"
docker run --add-host "aihack.docker:host-gateway" \
           --detach \
           --name "$DUSK_CONTAINER_NAME" \
           --network "st-internal" \
           --shm-size "2g" \
           "$DUSK_CONTAINER_IMAGE" >/dev/null

COMMAND=dusk

if [ "$1" == "--fails" ]; then
    COMMAND=dusk:fails
    shift
fi

sleep 1;

echo "Running Dusk Tests"
docker exec -it "$CODE_CONTAINER_NAME" php artisan "$COMMAND" "$@"
