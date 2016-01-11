# Http App

[![Build Status](https://img.shields.io/travis/weew/php-app-http.svg)](https://travis-ci.org/weew/php-app-http)
[![Code Quality](https://img.shields.io/scrutinizer/g/weew/php-app-http.svg)](https://scrutinizer-ci.com/g/weew/php-app-http)
[![Test Coverage](https://img.shields.io/coveralls/weew/php-app-http.svg)](https://coveralls.io/github/weew/php-app-http)
[![Version](https://img.shields.io/packagist/v/weew/php-app-http.svg)](https://packagist.org/packages/weew/php-app-http)
[![Licence](https://img.shields.io/packagist/l/weew/php-app-http.svg)](https://packagist.org/packages/weew/php-app-http)

## Table of contents

- [Installation](#installation)
- [Introduction](#introduction)
- [Usage](#usage)

## Installation

`composer require weew/php-app-http`

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

