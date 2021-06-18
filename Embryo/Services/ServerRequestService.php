<?php 

    /**
     * ServerRequestService
     */

    namespace Embryo\Services;

    use Embryo\Container\ServiceProvider;
    use Embryo\Http\Factory\ServerRequestFactory;
    use Embryo\Http\Message\ServerRequest;
    
    class ServerRequestService extends ServiceProvider
    {
        /**
         * Registers service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->container->set('request', function() {
                return (new ServerRequestFactory)->createServerRequestFromServer();
            });

            $this->container->alias(ServerRequest::class, 'request');
        }
    }