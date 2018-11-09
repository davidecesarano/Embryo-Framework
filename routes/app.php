<?php

    $app->get('/', function($request, $response){
       
        $view = $this->get('view');
        return $view->render($response, 'home', ['name' => 'Hello World!']);

    });