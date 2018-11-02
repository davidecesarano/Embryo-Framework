<?php 

    namespace Embryo\Application;

    class Facade 
    {
        public static $app;
        public static $name;

        public static function init($app)
        {
            Facade::$app = $app;
        }

        public static function __callStatic($method, $args)
        {
            return static::self()->$method(...$args);
        }
    }