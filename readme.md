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

## Extensions

There are several extensions available:

- [weew/php-http-app-request-handler](https://github.com/weew/php-http-app-request-handler)
