#!/bin/sh

set -ex

if [ -n "$TRAVIS_JOB_ID" ]
then
    # Install Lumen, migrate DB, start Lumen (background), run tests and exit
    composer install
    php artisan migrate
    php -S lumen:8000 -t public &
    set +e
    while true
    do
        curl -fs lumen:8000 > /dev/null
        [ $? -eq 0 ] && break
        sleep 1
    done
    vendor/bin/phpunit -v
else
    # Install Lumen, migrate DB, start Lumen (foreground) and keep running
    composer install
    php artisan migrate
    php -S lumen:8000 -t public
fi
