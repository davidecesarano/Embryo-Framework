<?php 

    /**
     * CacheService
     */

    namespace App\Services;

    use Embryo\Container\ServiceProvider;
    use Embryo\Cache\Cache;
    
    class CacheService extends ServiceProvider
    {
        /**
         * Registers service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->container->set('cache', function($container){
                $settings = $container->get('settings');
                return new Cache($settings['cache']['cachePath']);
            });
        }
    }