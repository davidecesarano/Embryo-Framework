<?php 

    /**
     * Container
     * 
     * Facade for Container service.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link https://github.com/davidecesarano/embryo-framework
     */
    
    namespace Embryo\Facades;

    use Embryo\Facade;
    use Psr\Container\ContainerInterface;

    class Container extends Facade 
    {
        /**
         * Return container.
         *
         * @return ContainerInterface
         */
        public static function self(): ContainerInterface
        {
            return self::$container;
        }
    }