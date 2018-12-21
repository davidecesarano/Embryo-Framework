<?php 

    /*
    |--------------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------------
    */

    $app->service(App\Services\ServerRequestService::class);
    $app->service(App\Services\ResponseService::class);
    $app->service(App\Services\BaseUrlService::class);
    $app->service(App\Services\BasePathService::class);
    $app->service(App\Services\MiddlewareDispatcherService::class);
    $app->service(App\Services\RouterService::class);
    $app->service(App\Services\LoggerService::class);
    $app->service(App\Services\ErrorHandlerService::class);
    $app->service(App\Services\CacheService::class);
    $app->service(App\Services\ViewService::class);
    $app->service(App\Services\DatabaseService::class);
    $app->service(App\Services\MailService::class);
    $app->service(App\Services\ValidationService::class);
    $app->service(App\Services\TranslateService::class);