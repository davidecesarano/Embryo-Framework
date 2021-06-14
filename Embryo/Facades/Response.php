<?php 

    /**
     * Response
     * 
     * Facade for Response service.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link https://github.com/davidecesarano/embryo-framework
     */
    
    namespace Embryo\Facades;

    use Embryo\Facade;
    
    class Response extends Facade 
    {
        /**
         * Return service name.
         *
         * @return string
         */
        public static function getFacadeAccessor(): string
        {
            return 'response';
        }
    }