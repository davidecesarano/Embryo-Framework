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
    use Embryo\Container\Interfaces\ContainerBuilderInterface;

    /**
     * Container
     * 
     * @method static mixed get($key)
     */
    
     class Container extends Facade 
    {
        /**
         * Return container.
         *
         * @return ContainerBuilderInterface
         */
        public static function self(): ContainerBuilderInterface
        {
            return self::$container;
        }
    }