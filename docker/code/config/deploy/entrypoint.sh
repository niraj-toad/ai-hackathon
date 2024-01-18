#!/bin/sh

set -eo pipefail

if [ -z "$S3_LOCATION" ] || [ -z "$ENV" ]; then
    echo "S3_LOCATION & ENV needs to be set. This will exit now.";
    exit 1;
fi

aws s3 cp "s3://${S3_LOCATION}/config/${ENV}.env" /opt/php/.env

touch /opt/php/rds-combined-ca-bundle.pem;
wget -O /opt/php/rds-combined-ca-bundle.pem https://truststore.pki.rds.amazonaws.com/global/global-bundle.pem || true;

if [ -n "$APP_ENV_PATH" ]; then
    echo "Overriding .env path with: $APP_ENV_PATH";

    SENTRY_VERSION=$(cat /var/www/html/sentry_version)
    echo -e "\nSENTRY_RELEASE=$SENTRY_VERSION" >> "${APP_ENV_PATH}/.env"

    VERSION=$(cat /var/www/html/version)
    echo -e "\nAPP_RELEASE=$VERSION" >> "${APP_ENV_PATH}/.env"
fi

cd /var/www/html || exit 1

su -s /bin/sh www-data -c "php artisan optimize"
su -s /bin/sh www-data -c "php artisan event:cache"
su -s /bin/sh www-data -c "php artisan view:cache"

if [ -n "$RUN_MIGRATIONS" ]; then
    su -s /bin/sh www-data -c "php artisan migrate --no-interaction --force"
else
    /usr/bin/supervisord -c /etc/supervisord.conf
fi
