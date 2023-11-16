FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
        zip unzip bash-completion libgmp-dev \
        && docker-php-ext-install sockets \
        && docker-php-ext-install gmp

RUN pecl install install -f xdebug-3.1.3 \
        && docker-php-ext-enable xdebug
COPY ./conf.d /usr/local/etc/php/conf.d

COPY --from=composer /usr/bin/composer /usr/bin/composer

ARG UID
ARG USER_NAME

RUN useradd -m -u ${UID} -g root ${USER_NAME}