<?php 

    /**
     * BasePathService 
     */

    namespace App\Services;

    use Embryo\Container\ServiceProvider;

    class BasePathService extends ServiceProvider
    {
        /**
         * Registers service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->container->set('basePath', function($container){

                $settings = $container['settings']['app'];
                if ($settings['path'] == '') {
                    $server   = $container['request']->getServerParams();
                    $script   = parse_url($server['SCRIPT_NAME'], PHP_URL_PATH);
                    $basePath = dirname($script);
                } else {
                    $basePath = $settings['path'];
                }
                return $basePath;

            });
        }
    }