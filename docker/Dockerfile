ARG PHP_IMAGE_TAG=8.0-fpm-alpine3.13

FROM php:$PHP_IMAGE_TAG

COPY entrypoint.sh /

WORKDIR /var/www/html

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    # FIXME: Check for a new hash & change it with a new version of composer.
    php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer

# Install PDO extension for MySQL/MariaDB
RUN docker-php-ext-install pdo_mysql

# Requirements for production deployment
RUN apk add findutils gnupg lftp
