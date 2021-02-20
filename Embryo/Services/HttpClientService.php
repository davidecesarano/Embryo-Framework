<?php 

    /**
     * HttpClientService 
     */

    namespace Embryo\Services;

    use Embryo\Container\ServiceProvider;
    use Embryo\Http\Client\ClientFactory;

    class HttpClientService extends ServiceProvider
    {   
        /**
         * Registers service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->container->set(ClientFactory::class, function($container) {
                return new ClientFactory;
            });
            
            $this->container->alias('http', ClientFactory::class);
        }
    }