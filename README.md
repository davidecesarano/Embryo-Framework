# Embryo Framework
Embryo is a simple PHP framework for building web applications.

## Features
### PSR Support
Embryo support [PSR-7](https://www.php-fig.org/psr/psr-7) HTTP Message, [PSR-17](https://www.php-fig.org/psr/psr-17) HTTP Factories, [PSR-15](https://www.php-fig.org/psr/psr-15) HTTP Handlers, [PSR-16](https://www.php-fig.org/psr/psr-16) Simple Cache and [PSR-11](https://www.php-fig.org/psr/psr-11) Container. 

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

## Example
First, instantiate the Embryo application: 
```php
$app = new Embryo\Application;
```

Later, you need to create the `Request`, `Response`, `RequestHandler` and `Router` services:
```php
$app->service(function($container){
    $container->set('request', function(){
        return (new Embryo\Http\Factory\ServerRequestFactory)->createServerRequestFromServer();
    });
});

$app->service(function($container){
    $container->set('response', function(){
        return (new Embryo\Http\Factory\ResponseFactory)->createResponse(200);
    });
});

$app->service(function($container){
    $container->set('requestHandler', function(){
        return new Embryo\Http\Server\RequestHandler;
    });
});

$app->service(function($container){
    $container->set('router', function(){
        return new Embryo\Routing\Router;
    });
});
```

Now, you can define the Embryo application routes:
```php
$app->get('/', function ($request, $response) {
    return $response->write('Hello World!');
});

//...
```

Finally, run application:
```php
$app->run();
```

You may quickly test this using the built-in PHP server:
```
$ cd example
$ php -S localhost:8080
```
Going to http://localhost:8080 will now display "Hello World!".