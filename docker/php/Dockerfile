FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
        zip unzip bash-completion \
        && docker-php-ext-install sockets

RUN pecl install xdebug && docker-php-ext-enable xdebug && \
    echo "xdebug.remote_enable = 1 \n\
    xdebug.remote_connect_back = 0 \n\
    xdebug.remote_port = 9009 \n\
    xdebug.max_nesting_level = 512 \n\
    xdebug.idekey = PHPSTORM \n\
    xdebug.remote_host = 172.17.0.1 \n\
    xdebug.remote_autostart = on \n " >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN curl -sS https://getcomposer.org/installer | \
            php -- --install-dir=/usr/bin/ --filename=composer

ARG UID
ARG USER_NAME

RUN useradd -m -u ${UID} -g root ${USER_NAME}