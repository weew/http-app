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

Sometimes you might want, for example during tests, to send a request to the same front controller but in a different environment. Symfony does this using different front controllers: `app.php` and `app_dev.php`. This approach will however alter the url and might not be suitable in some situations. Beside creating different front controllers you can also specify an environment using the `x-env` header with a value like `prod`, `dev`, etc. This feature is disabled by default and you can enable it by setting `environment_aware` to true inside the config object.

 ```php
 $app->getConfig()->set('environment_aware', true);
 $request = new HttpRequest();
 $request->getHeaders()->set('x-env', 'integration');

 // app will run in the "integration" environment
 $app->handle($request);
 ```

You must not use this in production! This is why it is disabled by default. Only enable this feature for your dev environment.

## Extensions

There are several extensions available:

- [weew/php-http-app-request-handler](https://github.com/weew/php-http-app-request-handler)
