FROM php:8.0-rc-cli

COPY opcache.ini /usr/local/etc/php/conf.d/

RUN apt-get update && apt-get upgrade -y \
    && apt-get install apt-utils -y \
#
#    устанавливаем необходимые пакеты
    && apt-get install git zip vim libzip-dev libgmp-dev libffi-dev libssl-dev -y \
#
#    Включаем необходимые расширения
    && docker-php-ext-install -j$(nproc) sockets zip gmp pcntl bcmath ffi \
#
#    Расшерения через pecl ставятся так, то в php8 pecl сейчас отсуствует, так что строки закоментированы
#    && PHP_OPENSSL=yes pecl install ev \
#    && docker-php-ext-enable ev \
#
#    Чистим временные файлы
    && docker-php-source delete \
    && apt-get autoremove --purge -y && apt-get autoclean -y && apt-get clean -y
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    && php -r "if (hash_file('sha384', 'composer-setup.php') === 'c31c1e292ad7be5f49291169c0ac8f683499edddcfd4e42232982d0fd193004208a58ff6f353fde0012d35fdd72bc394') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
    && php composer-setup.php
    && php -r "unlink('composer-setup.php');"
    && php composer.phar install
