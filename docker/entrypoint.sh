#!/bin/sh

set -x

if [ -n "$TRAVIS_JOB_ID" ]
then
    composer install && php artisan migrate && php -S lumen:8000 -t public &
    while true
    do
        curl -fs lumen:8000 > /dev/null
        [ $? -eq 0 ] && break
        sleep 1
    done
    vendor/bin/phpunit -v
else
    composer install && php artisan migrate && php -S lumen:8000 -t public
fi
