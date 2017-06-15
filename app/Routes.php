<?php 
    
    use Core\Router;
    
    $router = new Router;
			
    /**
     * Home Page
     */
    $router->get('', 'Page@index');
    
    /**
     * Traduzioni
     */
    $router->get('it', ['middleware' => 'Language:it', 'Page@index']);
    $router->get('en', ['middleware' => 'Language:en', 'Page@index']);
    
    /**
     * Page
     */
    $router->get('example', 'Page@example');
       
    /**
     * ------------------------------------------------------------
     * ESECUZIONE ROUTES
     * ------------------------------------------------------------
     */
    $router->execute();