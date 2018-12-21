<?php

    $app->get('/', function($request, $response){

        $view = $this->get('view');
        return $view->render($response, 'home', ['title' => 'Embryo 2']);
        
    });

    $app->get('/controller/{name}', 'PageController@index');