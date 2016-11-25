<?php 
    
    use Core\Router;
    
    $router = new Router;
			
    /**
     * Home Page
     */
    $router->get('', 'Page@index');
    
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