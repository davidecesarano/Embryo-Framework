<?php
    
    /*
    |--------------------------------------------------------------------------
    | Middleware PSR-15
    |--------------------------------------------------------------------------
    */

    $container = $app->getContainer();
    $settings  = $container['settings'];
    $router    = $container['router'];

    // error middleware
    $app->addMiddleware(
        (new Embryo\Error\Middleware\ErrorHandlerMiddleware)
            ->setErrorHandler($container['errorHandler'])
    );

    // session middleware
    $app->addMiddleware(
        (new Embryo\Session\Middleware\SessionMiddleware)
            ->setName($settings['session']['name'])
            ->setOptions($settings['session']['options'])
    );

    // secure headers middleware
    $app->addMiddleware(App\Middleware\SecureHeadersMiddleware::class);

    // routing middlewares
    $app->addMiddleware(Embryo\Routing\Middleware\MethodOverrideMiddleware::class);
    $app->addMiddleware(new Embryo\Routing\Middleware\RoutingMiddleware($router));
    $app->addMiddleware(new Embryo\Routing\Middleware\RequestHandlerMiddleware($container));