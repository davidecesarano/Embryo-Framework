<?php 

    /**
     * Log
     * 
     * Facade for Log service.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link https://github.com/davidecesarano/embryo-framework
     */
    
    namespace Embryo\Facades;

    use Embryo\Facade;
    use Embryo\Container\Interfaces\ContainerBuilderInterface;

    /**
     * Log
     * 
     * @method static mixed get($key)
     */
    
    class Log extends Facade 
    {
        /**
         * Return container.
         *
         * @return ContainerBuilderInterface
         */
        public static function self(): ContainerBuilderInterface
        {
            return self::$container->get('log');
        }
    }