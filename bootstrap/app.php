<?php 

    use Embryo\Application\Application as App;
    use Embryo\Application\Facade;

    /*
    |--------------------------------------------------------------------------
    | DOTENV
    |--------------------------------------------------------------------------
    */

    $dotenv = new Dotenv\Dotenv(ROOT_PATH);
    $dotenv->load();

    /*
    |--------------------------------------------------------------------------
    | SETTINGS
    |--------------------------------------------------------------------------
    */
    
    $settings = require_once ROOT_PATH.'bootstrap/settings.php';

    /*
    |--------------------------------------------------------------------------
    | APPLICATION
    |--------------------------------------------------------------------------
    */

    $app = new App($settings);

    /*
    |--------------------------------------------------------------------------
    | SERVICES
    |--------------------------------------------------------------------------
    */

    require_once ROOT_PATH.'bootstrap/services.php';

    /*
    |--------------------------------------------------------------------------
    | FACADE
    |--------------------------------------------------------------------------
    */
    
    Facade::init($app->getContainer());

    /*
    |--------------------------------------------------------------------------
    | MIDDLEWARES
    |--------------------------------------------------------------------------
    */
    
    require_once ROOT_PATH.'bootstrap/middleware.php';

    /*
    |--------------------------------------------------------------------------
    | ROUTES
    |--------------------------------------------------------------------------
    */

    require_once ROOT_PATH.'routes/app.php';
    require_once ROOT_PATH.'routes/api.php';

    /*
    |--------------------------------------------------------------------------
    | RUN APPLICATION
    |--------------------------------------------------------------------------
    */

    return $app;