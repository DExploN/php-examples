FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
        zip unzip bash-completion libgmp-dev \
        && docker-php-ext-install sockets \
        && docker-php-ext-install gmp

RUN pecl install -f xdebug-3.1.3 \
        && docker-php-ext-enable xdebug

RUN apt-get install -y librdkafka-dev \
        && pecl install -f rdkafka-6.0.3 \
        && docker-php-ext-enable rdkafka

COPY ./ini_dir $PHP_INI_DIR

COPY --from=composer /usr/bin/composer /usr/bin/composer

ARG UID
ARG USER_NAME

RUN useradd -m -u ${UID} -g root ${USER_NAME}