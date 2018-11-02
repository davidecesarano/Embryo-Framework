<?php 

    /**
     * RouterService
     */

    namespace App\Services; 

    use Embryo\Container\ServiceProvider;
    use Embryo\Routing\Router;

    class RouterService extends ServiceProvider
    {
        /**
         * Registers service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->container->set('router', function($container){
                
                $router = new Router;
                $router = $router->setNamespace($container['settings']['routing']['namespace']);
                $router = $router->setBasePath($container['basePath']);
                return $router;

            });
        }
    }