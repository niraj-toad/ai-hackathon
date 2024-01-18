FROM nginx:stable-alpine as base
FROM base as deploy

RUN apk -U upgrade && apk add --no-cache util-linux

COPY ./docker/nginx/config/deploy/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/nginx/config/deploy/self-signed.com.* /etc/nginx/

RUN mkdir -p /var/www/html/public
COPY ./public /var/www/html/public

VOLUME /var/lib/amazon
VOLUME /var/log/amazon
VOLUME /run
VOLUME /var/cache/nginx

WORKDIR /var/www/html

EXPOSE 80 443
