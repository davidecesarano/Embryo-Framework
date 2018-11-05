<?php 
    
    /**
     * ------------------------------------------------------------
     * ERRORS
     * ------------------------------------------------------------
     */
     
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    /**
     * ------------------------------------------------------------
     * PATHS    
     * ------------------------------------------------------------
     */

    define('ROOT_PATH', realpath(__DIR__.'/../').DIRECTORY_SEPARATOR);

    /**
     * ------------------------------------------------------------
     * AUTOLOAD PSR-4   
     * ------------------------------------------------------------
     */
     
    require ROOT_PATH.'vendor/autoload.php';
    
    /**
     * ------------------------------------------------------------
     * BOOT APPLICATION  
     * ------------------------------------------------------------
     */
    
    require_once ROOT_PATH.'boot/app.php';

    /**
     * ------------------------------------------------------------
     * RUN APPLICATION
     * ------------------------------------------------------------
     */

    $app->run();