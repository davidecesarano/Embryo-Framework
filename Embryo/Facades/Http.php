<?php 

    /**
     * Http
     * 
     * Facade for HttpClient service.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link https://github.com/davidecesarano/embryo-framework
     */
    
    namespace Embryo\Facades;

    use Embryo\Facade;
    use Embryo\Container\Interfaces\ContainerBuilderInterface;

    /**
     * Http
     * 
     * @method static mixed get($key)
     */
    
    class Http extends Facade 
    {
        /**
         * Return container.
         *
         * @return ContainerBuilderInterface
         */
        public static function self(): ContainerBuilderInterface
        {
            return self::$container->get('http');
        }
    }