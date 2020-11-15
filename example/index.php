<?php 

    /*
    |--------------------------------------------------------------------------
    | Require Composer's autoload
    |--------------------------------------------------------------------------
    */
    
    require __DIR__ . '/../vendor/autoload.php';

    /*
    |--------------------------------------------------------------------------
    | Instantiate a Embryo application
    |--------------------------------------------------------------------------
    */

    $app = new Embryo\Application;

    /*
    |--------------------------------------------------------------------------
    | Define the Embryo application routes
    |--------------------------------------------------------------------------
    */

    $app->get('/', function ($request, $response) {
        return $response->write('Hello World!');
    });

    $app->get('/hello[/{name}]', function ($request, $response, $name = null) {
        return $response->withJson([
            'result' => 'Hello '.$name
        ]);
    });

    /*
    |--------------------------------------------------------------------------
    | Run the Embryo application
    |--------------------------------------------------------------------------
    */

    $app->run();