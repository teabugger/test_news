ARG PHP_VERSION=7.4

# "php" stage
FROM php:${PHP_VERSION}-fpm-alpine AS php
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql
RUN apk add --no-cache autoconf rabbitmq-c-dev g++ cmake make
RUN pecl install amqp
RUN docker-php-ext-enable amqp
WORKDIR /srv/api
RUN wget "https://getcomposer.org/composer-2.phar" -O /usr/local/bin/composer && chmod +x /usr/local/bin/composer

FROM php as consumer
COPY consumer/news.sh /bin/commands/news.sh
RUN chmod a+x /bin/commands/news.sh

FROM nginx:stable-alpine as web
RUN apk add --no-cache bash curl
COPY nginx/nginx-start /usr/local/bin/
RUN chmod a+x /usr/local/bin/nginx-start
CMD ["nginx-start"]
