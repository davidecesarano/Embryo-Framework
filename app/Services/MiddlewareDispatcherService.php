<?php 

    /**
     * MiddlewareDispatcherService
     */

    namespace App\Services;

    use Embryo\Container\ServiceProvider;
    use Embryo\Http\Server\MiddlewareDispatcher;

    class MiddlewareDispatcherService extends ServiceProvider
    {
        /**
         * Registers service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->container->set('middlewareDispatcher', function(){
                $middleware = new MiddlewareDispatcher;
                return $middleware;
            });
        }
    }