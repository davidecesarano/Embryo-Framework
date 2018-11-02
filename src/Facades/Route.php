<?php 

    namespace Embryo\Facades;

    use Embryo\Application\Facade;

    class Route extends Facade 
    {
        public static function self()
        {
            return self::$app->getContainer()['router'];
        }
    }