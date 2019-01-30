<?php 

    /**
     * MiddlewareDispatcherService
     */

    namespace Embryo\Services;

    use Embryo\Container\ServiceProvider;
    use Embryo\Http\Server\RequestHandler;

    class RequestHandlerService extends ServiceProvider
    {
        /**
         * Registers service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->container->set('requestHandler', function(){
                return new RequestHandler;
            });
        }
    }