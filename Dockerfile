FROM php:7.4-fpm
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN echo "xdebug.remote_enable = 1 \n\
    xdebug.remote_connect_back = 0 \n\
    xdebug.remote_port = 9009 \n\
    xdebug.max_nesting_level = 512 \n\
    xdebug.idekey = PHPSTORM \n\
    xdebug.remote_host = 172.17.0.1 \n\
    xdebug.remote_autostart = on \n " >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini