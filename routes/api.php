<?php 

    $app->prefix('/api')->group(function($app){
        
        $app->get('/{name}', function($request, $response, $name){
            return [
                'result' => [
                    'name' => $name
                ]
            ];
        });

    });