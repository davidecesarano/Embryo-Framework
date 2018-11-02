<?php 

    namespace Embryo\Facades;

    use Embryo\Application\Facade;

    class Database extends Facade 
    {
        public static function self()
        {
            return self::$app->getContainer()['database'];
        }
    }