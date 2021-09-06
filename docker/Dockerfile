ARG PHP_IMAGE_TAG=8.0-fpm-alpine3.13

FROM php:$PHP_IMAGE_TAG

COPY entrypoint.sh /

WORKDIR /var/www/html

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    # FIXME: Check for a new hash & change it with a new version of composer.
    php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer

# Install PDO extension for MySQL/MariaDB
RUN docker-php-ext-install pdo_mysql
