# Embryo Framework
Embryo is a simple PHP framework for building web applications.
```php
require 'vendor/autoload.php';

$app = new Embryo\Application;

$app->get('/', function ($request, $response) {
    return $response->write('Hello World!');
});

$app->run();
```

## Features
### PSR Support
Embryo support [PSR-7](https://www.php-fig.org/psr/psr-7) HTTP Message, [PSR-17](https://www.php-fig.org/psr/psr-17) HTTP Factories, [PSR-15](https://www.php-fig.org/psr/psr-15) HTTP Server Request Handlers and [PSR-11](https://www.php-fig.org/psr/psr-11) Container. 

### HTTP Router
Embryo provides a PSR compatible router that maps route callbacks to specific HTTP request methods and URIs. It supports parameters and pattern matching. See [Embryo Routing](https://github.com/davidecesarano/Embryo-Routing). 

### Middleware
Embryo uses Middleware to manipulate the Request and Response object of application. Embryo support middlewares that implement PSR-15 HTTP Handlers. See [Embryo Middleware](https://github.com/davidecesarano/Embryo-Middleware). 

### Dependency Injection
Embryo uses a dependency container to create, manage ad inject application dependencies. Embryo support containers that implement PSR-11 Container Interface. See [Embryo Container](https://github.com/davidecesarano/Embryo-Container) 

## Requirements
* PHP >= 7.1
* URL Rewriting

## Installation
Using Composer:
```
$ composer require davidecesarano/embryo-framework
```

## Application
The easiest way to start working with Embryo is to create a project using [Embryo Skeleton Application](https://github.com/davidecesarano/Embryo) as a base.