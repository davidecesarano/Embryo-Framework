<?php
    
    /*
    |--------------------------------------------------------------------------
    | PSR-15 Middleware
    |--------------------------------------------------------------------------
    */

    $container = $app->getContainer();
    $settings  = $container['settings'];
    $error     = $container['errorHandler'];
    $session   = $settings['session'];
    $router    = $container['router'];

    // secure headers middleware
    $app->addMiddleware(App\Middleware\SecureHeadersMiddleware::class);

    // error middleware
    $app->addMiddleware(
        (new Embryo\Error\Middleware\ErrorHandlerMiddleware)
            ->setErrorHandler($error)
    );

    // session middleware
    $app->addMiddleware(
        (new Embryo\Session\Middleware\SessionMiddleware)
            ->setName($session['name'])
            ->setOptions($session['options'])
    );

    // csrf middleware
    $app->addMiddleware(Embryo\CSRF\CsrfMiddleware::class);

    // set locale language middleware
    $app->addMiddleware(
        (new App\Middleware\SetLocaleMiddleware)
            ->setLanguage($settings['app']['locale'])
    );

    // minify html
    $app->addMiddleware(Embryo\View\Middleware\MinifyHtmlMiddleware::class);

    // routing middlewares
    $app->addMiddleware(Embryo\Routing\Middleware\MethodOverrideMiddleware::class);
    $app->addMiddleware(new Embryo\Routing\Middleware\RoutingMiddleware($router));
    $app->addMiddleware(new Embryo\Routing\Middleware\RequestHandlerMiddleware($container));