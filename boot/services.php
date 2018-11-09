<?php 

    /*
    |--------------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    $app->service(App\Services\ServerRequestService::class);
    $app->service(App\Services\ResponseService::class);
    $app->service(App\Services\BaseUrlService::class);
    $app->service(App\Services\BasePathService::class);
    $app->service(App\Services\MiddlewareDispatcherService::class);
    $app->service(App\Services\RouterService::class);
    $app->service(App\Services\ViewService::class);
    $app->service(App\Services\LoggerService::class);
    $app->service(App\Services\ErrorHandlerService::class);