#!/bin/sh

set -ex

# Check PHP version
php -v

if [[ "$CTVERO_DEPLOY_PROD" == "true" ]]
then
    # Decrypt .env, install Lumen, upload content to the FTP and migrate DB via HTTP request
    # Clean up the tree from artifacts of previous (unsuccessful) runs
    rm -rf composer.lock deploy-prod-files/.env vendor/
    # Do NOT expose more than necessary!
    set +x
    [ -z "$CTVERO_DEPLOY_PROD_SECRET" ] && exit 1
    gpg --no-options --quiet --batch --yes --decrypt --passphrase="$CTVERO_DEPLOY_PROD_SECRET" --output deploy-prod-files/.env deploy-prod-files/.env.gpg
    composer install --no-dev
    # Dereference symbolic links due to some FTP server limitations
    find -type l -exec sh -c 'cp `readlink -fn {}` {}' \;
    set -o allexport
    source deploy-prod-files/.env
    LFTP_PASSWORD="$CTVERO_DEPLOY_PROD_FTP_PASSWORD"
    set +o allexport
    # Delete configuration/secrets from .env which are ONLY related to this deployment
    # So that we don't upload them to the server
    sed -i "/^CTVERO_DEPLOY_PROD_/d" deploy-prod-files/.env
    # The FTP directory must be explicitly set, even if it was "/" (to avoid accidental overwrites)
    # Expose the target FTP directory
    set -x
    [ -z "$CTVERO_DEPLOY_PROD_FTP_DIRECTORY" ] && exit 1
    # From here, do NOT expose more than necessary!
    set +x
    {
        echo "mkdir -pf $CTVERO_DEPLOY_PROD_FTP_DIRECTORY"
        echo "cd $CTVERO_DEPLOY_PROD_FTP_DIRECTORY"
        cat deploy-prod-files/lftp-commands
    } | lftp --env-password "ftp://$CTVERO_DEPLOY_PROD_FTP_USERNAME@$CTVERO_DEPLOY_PROD_FTP_SERVER"
    [[ "`curl -sH "X-Ctvero-API-Admin-Secret: $CTVERO_API_ADMIN_SECRET" ${APP_URL/http:/https:}/api/v0/app/migrate`" -eq "0" ]]
    # Clean up the tree from deployment artifacts (expose this action)
    set -x
    rm -rf composer.lock deploy-prod-files/.env vendor/
elif [[ "$GITHUB_ACTIONS" == "true" ]]
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
