<?php 

    /**
     * Controller
     */
    $app->get('/controller-1', 'Page@index');

    /**
     * Controller (param)
     */
    $app->get('/controller-2/{param}', 'Page@param');

    /**
     * Controller (param e where)
     */
    $app->get('/controller-3/{int}', 'Page@int')->where('int', '[0-9]+');

    /**
     * Controller (param, where e middleware)
     */
    $app->get('/controller-4/{int}', 'Page@middleware')->middleware('App\Middleware\Test')->where('int', '[0-9]+');