# Čtvero ročních období (CB soutěž) / The Four Seasons (CB Contest)

## This Application

[![Test](https://github.com/fivaldi/ctvero-rocnich-obdobi/actions/workflows/test.yml/badge.svg)](https://github.com/fivaldi/ctvero-rocnich-obdobi/actions/workflows/test.yml)
[![Prod](https://github.com/fivaldi/ctvero-rocnich-obdobi/actions/workflows/prod.yml/badge.svg)](https://github.com/fivaldi/ctvero-rocnich-obdobi/actions/workflows/prod.yml)

### Development and Testing

Providing you have `docker` and `docker-compose` installed, clone the repository and run:

```
UID_GID="$(id -u):$(id -g)" docker-compose up db lumen
```

Then check your browser at <http://localhost:8000>.

In a separate console tab/window, you may attach to the running containers and perform various actions:

```
docker exec -it ctvero-lumen sh  # for the app server, then run e.g.: composer, artisan...
docker exec -it ctvero-db sh  # for the db server
```

Local tests can be run as follows:

```
docker exec ctvero-lumen vendor/bin/phpunit -v
```

### Deployment

#### Automated Workflow (preferred)

Long story short: After a successfully tested PR merge to the production branch (`main`), the application gets deployed.

As of 2021/11, we're using GitHub Actions for this.
There's a strong secret (see `CTVERO_DEPLOY_PROD_SECRET` environment variable) which decrypts the file `deploy-prod-files/.env.gpg`. This file contains all other secrets which are necessary for application deployment and runtime. See also `docker/entrypoint.sh`, `docker-compose.yml` and `.github/workflows/main.yml`.

#### Manual Workflow

Prerequisites:

- Locally cloned up-to-date production branch (`main`) which has successful tests in GitHub Actions CI. (See above.)
- No `ctvero-*` docker containers/images artifacts are present. (Check using `docker ps -a`, `docker images` and/or remove those artifacts using `docker rm`, `docker rmi`.)
- No repository artifacts are present. (Check using `git status --ignored` and/or remove those artifacts using `git clean -fdx`.)

Deployment Steps:

```
docker-compose build --build-arg PHP_IMAGE_TAG=7.4-fpm-alpine3.13 deploy-prod  # for PHP 7.4 webhosting as of 2021/09
UID_GID="$(id -u):$(id -g)" CTVERO_DEPLOY_PROD_SECRET="some-very-long-secret" docker-compose up deploy-prod  # deploys the app
```

### License

This app is open-sourced software under the *Ham Spirit Transferred into IT* license :-).

## Lumen Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://img.shields.io/packagist/v/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://img.shields.io/packagist/l/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

### Official Documentation for Lumen

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

### Contributing

Thank you for considering contributing to Lumen! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

### Security Vulnerabilities

If you discover a security vulnerability within Lumen, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

### License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
