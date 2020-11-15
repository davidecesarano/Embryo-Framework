<?php 

    /**
     * Facade
     * 
     * Provide a static interface to classes that are available 
     * in the application's service container.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link https://github.com/davidecesarano/embryo-framework
     */

    namespace Embryo;

    use Psr\Container\ContainerInterface;

    class Facade 
    {
        /**
         * @var ContainerInterface $container
         */
        public static $container;

        /**
         * Set Container.
         *
         * @param ContainerInterface $container
         * @return void
         */
        public static function init(ContainerInterface $container)
        {
            Facade::$container = $container;
        }

        /**
         * Invoking container service.
         *
         * @param string $method
         * @param string[] $args
         * @return mixed
         */
        public static function __callStatic(string $method, $args)
        {
            return static::self()->$method(...$args);
        }
    }