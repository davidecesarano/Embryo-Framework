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

    use Embryo\Container\Interfaces\ContainerBuilderInterface;

    class Facade 
    {
        /**
         * @var ContainerBuilderInterface $container
         */
        public static $container;

        /**
         * Set Container.
         *
         * @param ContainerBuilderInterface $container
         * @return void
         */
        public static function init(ContainerBuilderInterface $container)
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

        /**
         * Set the instance which needs facades.
         * 
         * @return ContainerBuilderInterface
         */
        public static function self(): ContainerBuilderInterface
        {
            return Facade::$container;
        }
    }