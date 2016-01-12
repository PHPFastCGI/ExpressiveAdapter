# PHPFastCGI Zend Expressive Adapter

[![Latest Stable Version](https://poser.pugx.org/phpfastcgi/expressive-adapter/v/stable)](https://packagist.org/packages/phpfastcgi/expressive-adapter)
[![Build Status](https://travis-ci.org/PHPFastCGI/ExpressiveAdapter.svg?branch=master)](https://travis-ci.org/PHPFastCGI/ExpressiveAdapter)
[![Coverage Status](https://coveralls.io/repos/PHPFastCGI/ExpressiveAdapter/badge.svg?branch=master&service=github)](https://coveralls.io/github/PHPFastCGI/ExpressiveAdapter?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/PHPFastCGI/ExpressiveAdapter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/PHPFastCGI/ExpressiveAdapter/?branch=master&no-cache=1)
[![Total Downloads](https://poser.pugx.org/phpfastcgi/expressive-adapter/downloads)](https://packagist.org/packages/phpfastcgi/expressive-adapter)

A PHP package which allows Zend Expressive applications to reduce overheads by exposing their Request-Response structure to a FastCGI daemon.

Visit the [project website](http://phpfastcgi.github.io/).

## Introduction

Using this package, Zend Expressive applications can stay alive between HTTP requests whilst operating behind the protection of a FastCGI enabled web server.

## Current Status

This project is currently in early stages of development and not considered stable. Importantly, this library currently lacks support for uploaded files.

Contributions and suggestions are welcome.

## Installing

```sh
composer require "phpfastcgi/expressive-adapter:^0.1"
```

## Usage

```php
<?php // command.php

// Include the composer autoloader
require_once dirname(__FILE__) . '/../vendor/autoload.php';

use PHPFastCGI\FastCGIDaemon\ApplicationFactory;
use PHPFastCGI\Adapter\Expressive\ApplicationWrapper;
use Zend\Expressive\AppFactory;

// Create your Expressive app
$app = AppFactory::create();
$app->get('/', function ($request, $response, $next) {
    $response->getBody()->write('Hello, World!');
    return $response;
});

// Create the kernel for the FastCGIDaemon library (from the Expressive app)
$kernel = new ApplicationWrapper($app);

// Create the symfony console application
$consoleApplication = (new ApplicationFactory)->createApplication($kernel);

// Run the symfony console application
$consoleApplication->run();
```

If you wish to configure your FastCGI application to work with the apache web server, you can use the apache FastCGI module to process manage your application.

This can be done by creating a FastCGI script that launches your application and inserting a FastCgiServer directive into your virtual host configuration.

```sh
#!/bin/bash
php /path/to/command.php run
```

```
FastCgiServer /path/to/web/root/script.fcgi
```

By default, the daemon will listen on FCGI_LISTENSOCK_FILENO, but it can also be configured to listen on a TCP address. For example:

```sh
php /path/to/command.php run --port=5000 --host=localhost
```

If you are using a web server such as nginx, you will need to use a process manager to monitor and run your application.

## AstroSplash: An Example

The [AstroSplash](http://astrosplash.com/) website currently runs using this adapter. If you are looking for an example integration, you may find the [source code repository](https://github.com/AndrewCarterUK/AstroSplash) helpful.

The two important files:

- **[fastcgi.php](https://github.com/AndrewCarterUK/AstroSplash/blob/master/fastcgi.php)**

This PHP script creates and runs a FastCGI application using the Zend Expressive application object.

- **[supervisord.conf](https://github.com/AndrewCarterUK/AstroSplash/blob/master/supervisord.conf)**

As NGINX is used, [supervisord](http://supervisord.org/) was chosen to manage instances of the FastCGI application. This would not be necessary using Apache as it has a process manager built into the FastCGI module. This configuration file provides instructions for supervising the FastCGI application instances.
