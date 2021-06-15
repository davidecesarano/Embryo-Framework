<?php 

    /**
     * View
     * 
     * Facade for View service.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link https://github.com/davidecesarano/embryo-framework
     */
    
    namespace Embryo\Facades;

    use Embryo\Facade;

    /**
     * View
     * 
     * @method static Psr\Http\Message\ResponseInterface render(Psr\Http\Message\ResponseInterface $response, string $template, array $data)
     */
    
    class View extends Facade 
    {
        /**
         * Return service name.
         *
         * @return string
         */
        public static function getFacadeAccessor(): string
        {
            return 'view';
        }
    }