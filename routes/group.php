<?php 

    $app->group(function($app){
        $app->get('/test', function($request, $response){
            return $response->write('Group');
        });
    });

    $app->middleware('App\Middleware\Test')->group(function($app){
        $app->get('/test2', function($request, $response){
            return $response->write('Group with 1 middleware');
        });
    });

    $app->prefix('/prefix')->group(function($app){
        $app->get('/test', function($request, $response){
            return $response->write('Group with prefix');
        });
    });

    $app->middleware('App\Middleware\Test')->prefix('/prefix')->group(function($app){
        $app->get('/test2', function($request, $response){
            return $response->write('Group with prefix and middleware');
        });
    });

    $app->middleware('App\Middleware\Test', 'App\Middleware\Test2')->prefix('/prefix')->group(function($app){
        $app->get('/test3', function($request, $response){
            return $response->write('Group with prefix and 2 middleware');
        });
    });

    $app->middleware('App\Middleware\Test')->prefix('/prefix')->group(function($app){
        $app->get('/test4', function($request, $response){
            return $response->write('Group with prefix, middleware group and middleware route');
        })->middleware('App\Middleware\Test2');
    });

    $app->middleware('App\Middleware\Test')->prefix('/prefix')->group(function($app){
        $app->get('/test5/{int}', function($request, $response, $int){
            return $response->write('Group with prefix, middleware group and middleware route and '.$int);
        })->middleware('App\Middleware\Test2');
    });

    $app->middleware('App\Middleware\Test')->prefix('/prefix')->group(function($app){
        $app->get('/test6/{int}/ciao[/{param}]', function($request, $response, $int, $param = null){
            if (!$param) {
                return $response->write('Group with prefix, middleware group and middleware route and '.$int);
            }else{
                return $response->write('Group with prefix, middleware group and middleware route and '.$int.' and '.$param);
            }
        })->middleware('App\Middleware\Test2');
    });