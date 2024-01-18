FROM php:8.3-fpm-alpine AS base

RUN apk -U add --no-cache \
    bash zip unzip gzip wget libzip libgomp libzip-dev libpng libpng-dev freetype-dev icu icu-dev libjpeg \
    libjpeg-turbo-dev libpq perl shadow libpq-dev linux-headers util-linux && \
    apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS && \
    pecl install redis && \
    docker-php-ext-configure gd --with-jpeg --with-freetype && \
    docker-php-ext-enable redis && \
    docker-php-ext-install gd calendar zip bz2 intl bcmath opcache pdo pdo_pgsql && \
    apk del .phpize-deps

EXPOSE 9000

# Local (Developer)
FROM base AS local

RUN apk -U add --no-cache mariadb-client nodejs npm && \
    apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    apk del .phpize-deps

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY config/local/php.ini /usr/local/etc/php/

RUN sed -i -e "s/pm.max_children\s*=\s*[0-9]*/pm.max_children = 30/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i -e "s/pm.max_spare_servers\s*=\s*[0-9]*/pm.max_spare_servers = 10/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i -e "s/;pm.max_requests\s*=\s*[0-9]*/pm.max_requests = 500/g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i -e "s/access.log/;access.log/g" /usr/local/etc/php-fpm.d/docker.conf

WORKDIR /code

# Deployed (Prod/Non-Prod)
FROM base AS deploy

RUN apk -U upgrade && apk add --no-cache supervisor aws-cli screen

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini && \
    rm /usr/local/etc/php-fpm.d/docker.conf && \
    sed -i -e "s/;date.timezone\s*=.*/date.timezone = 'UTC'/" /usr/local/etc/php/php.ini && \
    sed -i -e "s/.*expose_php\s*=.*/expose_php = Off/" /usr/local/etc/php/php.ini && \
    sed -i -e "s/short_open_tag\s*=.*/short_open_tag = On/" /usr/local/etc/php/php.ini && \
    sed -i -e "s/max_execution_time\s*=.*/max_execution_time = 60/" /usr/local/etc/php/php.ini && \
    sed -i -e "s/memory_limit\s*=.*/memory_limit = 256M/" /usr/local/etc/php/php.ini && \
    sed -i -e "s/upload_max_filesize\s*=.*/upload_max_filesize = 64M/" /usr/local/etc/php/php.ini && \
    sed -i -e "s/post_max_size\s*=.*/post_max_size = 64M/" /usr/local/etc/php/php.ini && \
    sed -i -e "s/max_file_uploads\s*=.*/max_file_uploads = 100/" /usr/local/etc/php/php.ini && \
    sed -i -e "s/max_input_time\s*=.*/max_input_time = 180/" /usr/local/etc/php/php.ini && \
    sed -i -e "s/error_reporting\s*=.*/error_reporting = E_ALL \& \~E_NOTICE \& \~E_DEPRECATED \& \~E_STRICT \& \~E_WARNING/" /usr/local/etc/php/php.ini && \
    sed -i -e "s/;error_log\s*= syslog/error_log=\/proc\/self\/fd\/2/" /usr/local/etc/php/php.ini && \
    sed -i -e "s/;cgi.fix_pathinfo\s*=.*/cgi.fix_pathinfo=1/" /usr/local/etc/php/php.ini && \
    sed -i -e "s/session.gc_probability\s*=.*/session.gc_probability = 1/" /usr/local/etc/php/php.ini && \
    sed -i -e "s/;log_level\s*=.*/log_level = warning/" /usr/local/etc/php-fpm.conf && \
    sed -i -e "s/;error_log\s*=.*/error_log=\/proc\/self\/fd\/2/" /usr/local/etc/php-fpm.conf && \
    sed -i -e "s/;log_limit\s*=.*/log_limit = 8192/" /usr/local/etc/php-fpm.conf && \
    sed -i -e "s/;pid\s*=.*/pid=\/opt\/php\/php-fpm.pid/" /usr/local/etc/php-fpm.conf && \
    sed -i -e "s/;listen.backlog\s*=.*/listen.backlog = 2048/" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i -e "s/pm\s*=.*dynamic/pm = static/" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i -e "s/pm.max_children\s*=.*/pm.max_children = \${PHP_WORKERS}/" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i -e "s/pm.start_servers\s*=.*/pm.start_servers = 5/" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i -e "s/pm.min_spare_servers\s*=.*/pm.min_spare_servers = 5/" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i -e "s/pm.max_spare_servers\s*=.*/pm.max_spare_servers = 8/" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i -e "s/;pm.max_requests\s*=.*/pm.max_requests = 500/" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i -e "s/;catch_workers_output\s*=.*/catch_workers_output = yes/" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i -e "s/;decorate_workers_output\s*=.*/decorate_workers_output = no/" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i -e "s/;clear_env\s*=.*/clear_env = no/" /usr/local/etc/php-fpm.d/www.conf

COPY ./docker/code/config/deploy/supervisor.conf /etc/supervisord.conf
COPY ./docker/code/config/deploy/entrypoint.sh /usr/bin/entrypoint.sh
COPY ./docker/code/config/deploy/crontab /etc/crontabs/www-data
COPY ./docker/code/config/common/color_prompt.sh /etc/profile.d/color_prompt.sh
COPY ./docker/code/config/common/ecs-shell /usr/bin/ecs-shell

RUN chown -R www-data.www-data /var/www
COPY --chown=www-data:www-data ./ /var/www/html

RUN chmod 755 /usr/bin/entrypoint.sh && \
    touch /var/www/html/.env && \
    chmod 777 /tmp && \
    chmod 755 /usr/bin/ecs-shell && \
    rm -rf /var/www/html/docker

VOLUME /var/lib/amazon
VOLUME /var/log/amazon
VOLUME /var/www/html/bootstrap/cache
VOLUME /var/www/html/storage
VOLUME /opt/php
VOLUME /tmp

ENV ENV=/etc/profile.d/color_prompt.sh

WORKDIR /var/www

CMD ["/usr/bin/entrypoint.sh"]
