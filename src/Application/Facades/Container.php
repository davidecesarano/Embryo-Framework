<?php 

    /**
     * Container
     * 
     * Facade for Container object.
     * 
     * @author 
     * @
     */
    namespace Embryo\Application\Facades;

    use Embryo\Application\Facade;

    class Container extends Facade 
    {
        public static function self()
        {
            return self::$container;
        }
    }