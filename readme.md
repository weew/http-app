# Http App

[![Build Status](https://img.shields.io/travis/weew/php-http-app.svg)](https://travis-ci.org/weew/php-http-app)
[![Code Quality](https://img.shields.io/scrutinizer/g/weew/php-http-app.svg)](https://scrutinizer-ci.com/g/weew/php-http-app)
[![Test Coverage](https://img.shields.io/coveralls/weew/php-http-app.svg)](https://coveralls.io/github/weew/php-http-app)
[![Version](https://img.shields.io/packagist/v/weew/php-http-app.svg)](https://packagist.org/packages/weew/php-http-app)
[![Licence](https://img.shields.io/packagist/l/weew/php-http-app.svg)](https://packagist.org/packages/weew/php-http-app)

## Table of contents

- [Installation](#installation)
- [Introduction](#introduction)
- [Usage](#usage)
- [Environment awareness](#environment-awareness)
- [Extensions](#extensions)

## Installation

`composer require weew/php-http-app`

## Introduction

This is a very minimalistic wrapper for a http application.

## Usage

The whole app lifecycle is event based. To successfully handle http requests you must handle the `HandleHttpRequestEvent` and provide a valid response that implements the `IHttpResponse` interface.

Below is a very basic example of how you might implement this.

```php
$app = new HttpApp();
$app->getEventer()
    ->subscribe(HandleHttpRequestEvent::class, function(HandleHttpRequestEvent $event) {
        $request = $event->getRequest();

        // handle request (do some routing, call a controller, etc.)
        // provide a response that implements the IHttpResponse interface
        $event->setResponse($response);
    });
```

There is already an existing implementation for this, see [weew/php-http-app-request-handler](https://github.com/weew/php-http-app-request-handler).

## Environment awareness

Sometimes you might want, for example during tests, to send a request to the same front controller but in a different environment. Symfony does this using different front controllers: `app.php` and `app_dev.php`. This approach will however alter the url and might not be suitable in some situations. Beside creating different front controllers you can also specify an environment using the `x-env: dev` header or a query param `?env=dev` or a somewhere inside your url `/env=dev/some/url`. If an environment setting can be detected either via headers or url query or url part, the corresponding data (x-env header, env query param, env value inside the url) will be automatically removed. This feature is disabled by default and you can enable it by setting `debug` to true.

 ```php
 $app->setDebug(true);
 $request = new HttpRequest();

 $request->getHeaders()->set('x-env', 'stage');
 // or
 $request->getUrl()->getQuery()->set('env', 'stage');
 // or
 $request->getUrl()->setPath('/env=stage/some/url');

 // app will run in the "stage" environment
 $app->handle($request);
 ```

You must not use this in production! This is why it is disabled by default. Only enable this feature for your dev environment.

## Extensions

There are several extensions available:

- [weew/php-http-app-request-handler](https://github.com/weew/php-http-app-request-handler)
