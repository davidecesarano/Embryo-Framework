<?php 

    /**
     * CacheService
     */

    namespace Embryo\Services;

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
            $this->container->set(Cache::class, function($container){
                $settings = $container->get('settings');
                $cache = new Cache($settings['cache']['cachePath']);
                return $cache->setDefaultTtl($settings['cache']['ttl']);
            });

            $this->container->alias('cache', Cache::class);
        }
    }