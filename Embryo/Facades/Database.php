<?php 

    /**
     * Database
     * 
     * Facade for Database service.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link https://github.com/davidecesarano/embryo-framework
     */
    
    namespace Embryo\Facades;

    use Embryo\Facade;
    use Embryo\Container\Interfaces\ContainerBuilderInterface;

    /**
     * Database
     * 
     * @method static mixed get($key)
     */
    
    class Database extends Facade 
    {
        /**
         * Return container.
         *
         * @return ContainerBuilderInterface
         */
        public static function self(): ContainerBuilderInterface
        {
            return self::$container->get('database');
        }
    }