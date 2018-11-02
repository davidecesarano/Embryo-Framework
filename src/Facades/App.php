<?php 

    namespace Embryo\Facades;

    use Embryo\Application\Facade;

    class App extends Facade 
    {
        public static function self()
        {
            return self::$app;
        }
    }