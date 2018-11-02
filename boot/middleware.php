<?php
    
    /*
    |--------------------------------------------------------------------------
    | Middleware PSR-15
    |--------------------------------------------------------------------------
    |
    | Registra i middleware dell'applicazione. Gli oggetti devono essere
    | instanze di MiddlewareInterface (PSR 15).
    | 
    |
    */

    $container = $app->getContainer();
    $settings  = $container['settings'];

    //$app->addMiddleware(Embryo\Error\Middleware\ErrorHandlerMiddleware::class);
    $app->addMiddleware(
        (new Embryo\Session\Middleware\SessionMiddleware)
            ->setName($settings['session']['name'])
            ->setOptions($settings['session']['options'])
    );
    $app->addMiddleware(App\Middleware\SecureHeadersMiddleware::class);
    $app->addMiddleware(Embryo\Routing\Middleware\MethodOverrideMiddleware::class);
    $app->addMiddleware(new Embryo\Routing\Middleware\RoutingMiddleware($container['router']));
    $app->addMiddleware(new Embryo\Routing\Middleware\RequestHandlerMiddleware($container));