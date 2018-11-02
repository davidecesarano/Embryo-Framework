<?php 

    /**
     * ServerRequestService
     */

    namespace App\Services;

    use Embryo\Container\ServiceProvider;
    use Embryo\Http\Factory\ServerRequestFactory;
    
    class ServerRequestService extends ServiceProvider
    {
        /**
         * Registers service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->container->set('request', function(){
                $serverRequest = new ServerRequestFactory;
                return $serverRequest->createServerRequestFromServer();
            });
        }
    }