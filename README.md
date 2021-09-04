# Čtvero ročních období (CB soutěž) / The Four Seasons (CB Contest)

## This Application

[![Build Status](https://app.travis-ci.com/fivaldi/ctvero-rocnich-obdobi.svg?branch=main)](https://app.travis-ci.com/fivaldi/ctvero-rocnich-obdobi)

### Development and Testing

Providing you have `docker` and `docker-compose` installed, clone the repository and run:

```
docker-compose up
```

Then check your browser at <http://localhost:8000>.

In a separate console tab/window, you may attach to the running containers and perform various actions:

```
docker exec -it ctvero-lumen sh  # for the app server, then run e.g.: composer, artisan...
docker exec -it ctvero-db sh  # for the db server
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
