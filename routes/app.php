<?php

    /**
     * Home
     */
    $app->get('/', function($request, $response){
        return $response->write('Hello World!');
    });

    $app->get('/create', function($request, $response){

        $html = '<form method="post" action="" enctype="multipart/form-data">';
            $html .= '<input type="file" name="fileToUpload[]" id="fileToUpload" multiple>';
            $html .= '<input type="submit" value="Upload Image" name="submit">';
        $html .= '</form>';
        return $response->write($html);

    });

    $app->post('/create', function($request, $response){

        $files = $request->getUploadedFiles();
        foreach($files['fileToUpload'] as $file) {
            $file->moveTo('/mnt/c/Users/Dav.Cesarano/Dev/Local/git/embryo-framework/storage/');
        }
        echo '<pre>';
        print_r($files);
        print_r($_FILES);
 
    });

    $app->get('/edit', function($request, $response){

        $html = '<form method="post" action="">';
            $html .= '<input type="hidden" name="_METHOD" value="PUT">';
            $html .= '<input type="text" name="name">';
            $html .= '<input type="submit" value="Send" name="submit">';
        $html .= '</form>';
        return $response->write($html);

    });

    $app->put('/edit', function($request, $response){
        echo '<pre>';
        print_r($request);
    });

    /**
     * Callable
     */
    $app->get('/callable-1', function($request, $response){
        
        $view = $this->get('view');
        return $view->render($response, 'test', ['name' => 'Hello World!']);

    })->name('callable');

    /**
     * Callable (param)
     */
    $app->get('/callable-2', function($request, $response){

        $response = $response->write('<p>Callable 2</p>');
        return $response;

    });
    $app->get('/callable-2/{param}', function($request, $response, $param){

        echo '<pre>';
        print_r($request);

    })->name('callable-2-param');

    $app->get('/callables/{param}/ciao{/params}', function($request, $response, $param, $params = null){

        var_dump($params);
        $response = $response->write('<p>Callable con parametro '.$param.' e '.$params.'</p>');
        return $response;

    });
    
    /**
     * Callable (param e where)
     */
    $app->get('/callable-3/{int}', function($request, $response, $int){

        $response = $response->write('<p>Callable con parametro '.$int.'</p>');
        return $response;

    })->where('int', '[0-9]+');

    /**
     * Callable (param, where e middleware)
     */
    $app->get('/callable-4/{int}', function($request, $response, $int){
       
        $response = $response->write('<p>Callable con parametro intero '.$int.' e middleware</p>');
        return $response;
        
    })->middleware('App\Middleware\Test')->where('int', '[0-9]+');

    /**
     * Callable (param e where)
     */
    $app->get('/callable-5/{int}/{string?}', function($request, $response, $int, $string = null){

        $response = $response->write('<p>Callable con parametro intero '.$int.' e stringa '.$string.' e due middleware</p>');
        return $response;

    })->middleware('App\Middleware\Test', 'App\Middleware\Test2')->where([
        'int' => '[0-9]+'
    ]);

    $app->get('/ciao/{param}', function($request, $response, $param){
        echo $param;
    });

    $app->redirect('/redirect', '/callable-1', 302);