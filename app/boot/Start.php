<?php
    
    /**
     * ------------------------------------------------------------
     * PATHS	
     * ------------------------------------------------------------
     */
     
    require 'Paths.php';
    
    /**
     * ------------------------------------------------------------
     * CARTELLE	
     * ------------------------------------------------------------
     */
     
    require 'Folders.php';
    
    /**
     * ------------------------------------------------------------
     * AUTOLOAD PSR-4	
     * ------------------------------------------------------------
     */
     
    if(!file_exists(FOLDER_VENDOR.'/autoload.php')){
        die('File autoload.php non presente nella cartella vendor!');
    }else{
        require FOLDER_VENDOR.'/autoload.php';
    }
    
    /**
     * ------------------------------------------------------------
     * FUNZIONI	
     * ------------------------------------------------------------
     */
     
    require 'Functions.php';
    
    /**
     * ------------------------------------------------------------
     * AMBIENTE DI SVILUPPO	
     * ------------------------------------------------------------
     */
    
    require FOLDER_CONFIG.'/Error.php';
    enviroment();
    
    /**
     * ------------------------------------------------------------
     * GESTIONE DEGLI ERRORI
     * ------------------------------------------------------------
     */
    
    register_shutdown_function('Core\Error::shutdownHandler');
    set_error_handler('Core\Error::errorHandler');
    set_exception_handler('Core\Error::exceptionHandler');
    
    /**
     * ------------------------------------------------------------
     * CONFIG	
     * ------------------------------------------------------------
     */
     
    require FOLDER_CONFIG.'/Database.php';
    require FOLDER_CONFIG.'/Email.php';
    require FOLDER_CONFIG.'/App.php';
    require FOLDER_CONFIG.'/Api.php';
    require FOLDER_CONFIG.'/Alias.php';
    require FOLDER_CONFIG.'/Middlewares.php';
    require FOLDER_CONFIG.'/Widgets.php';
    require FOLDER_CONFIG.'/Session.php';
    
    /**
     * ------------------------------------------------------------
     * ALIAS CLASSI 'HELPERS'
     * ------------------------------------------------------------
     */
    
    helpers_alias();

    /**
     * ------------------------------------------------------------
     * AVVIO SESSIONE	
     * ------------------------------------------------------------
     */
    
    Session::init();
    
    /**
     * ------------------------------------------------------------
     * TOKEN
     * ------------------------------------------------------------
     */
    
    Token::set('public');
    
    /**
     * ------------------------------------------------------------
     * ROUTES
     * ------------------------------------------------------------
     */
     
    require 'app/Routes.php';