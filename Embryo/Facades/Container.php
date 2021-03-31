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

    /**
     * Container
     * 
     * @method static mixed get($key)
     */
    
    class Container extends Facade 
    {
        /**
         * Return service name.
         *
         * @return string
         */
        public static function getFacadeAccessor(): string
        {
            return '';
        }
    }