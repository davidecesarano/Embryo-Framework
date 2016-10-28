<?php 
    
    use Core\Router;
    use Helpers\Result;
    
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