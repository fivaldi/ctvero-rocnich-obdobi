ARG PHP_IMAGE_TAG=8.0-fpm-alpine3.13

FROM php:$PHP_IMAGE_TAG

COPY entrypoint.sh /

WORKDIR /var/www/html

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    # FIXME: Check for a new hash & change it with a new version of composer.
    php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer

# Install PDO extension for MySQL/MariaDB
RUN docker-php-ext-install pdo_mysql

# Requirements for production deployment
RUN apk add findutils gnupg lftp tzdata

# Set timezone
RUN cp /usr/share/zoneinfo/Europe/Prague /etc/localtime && \
    echo "Europe/Prague" > /etc/timezone
