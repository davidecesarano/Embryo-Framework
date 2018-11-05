<?php 


    /**
     * Facade
     */

    namespace Embryo\Application;

    use Psr\Container\ContainerInterface;

    class Facade 
    {
        public static $container;

        public static function init(ContainerInterface $container)
        {
            Facade::$container = $container;
        }

        public static function __callStatic(string $method, $args)
        {
            return static::self()->$method(...$args);
        }
    }