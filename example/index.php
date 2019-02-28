<?php 

    /*
    |--------------------------------------------------------------------------
    | 1 - Require Composer's autoload
    |--------------------------------------------------------------------------
    */
    
    require __DIR__ . '/../vendor/autoload.php';

    /*
    |--------------------------------------------------------------------------
    | 2 - Instantiate a Embryo application
    |--------------------------------------------------------------------------
    */

    $app = new Embryo\Application;

    /*
    |--------------------------------------------------------------------------
    | 3 - Define the basic Embryo application services
    |
    | -> PSR-7 Request
    | -> PSR-7 Response
    | -> PSR-15 RequestHandler
    | -> Router
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | 4 - Define the Embryo application routes
    |--------------------------------------------------------------------------
    */

    $app->get('/', function ($request, $response) {
        return $response->write('Hello World!');
    });

    $app->get('/hello[/{name}]', function ($request, $response, $name) {
        return $response->withJson([
            'result' => 'Hello '.$name
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | 5 - Define the basic Embryo application middleware
    |--------------------------------------------------------------------------
    */

    $container = $app->getContainer();
    $app->addMiddleware(new Embryo\Routing\Middleware\MethodOverrideMiddleware);
    $app->addMiddleware(new Embryo\Routing\Middleware\RoutingMiddleware($container['router']));
    $app->addMiddleware(new Embryo\Routing\Middleware\RequestHandlerMiddleware($container));                

    /*
    |--------------------------------------------------------------------------
    | 6 - Run the Embryo application
    |--------------------------------------------------------------------------
    */

    $app->run();