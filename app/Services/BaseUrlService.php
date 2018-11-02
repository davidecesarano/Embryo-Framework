<?php 

    /**
     * BaseUrlService 
     */

    namespace App\Services;

    use Embryo\Container\ServiceProvider;

    class BaseUrlService extends ServiceProvider
    {   
        /**
         * Registers service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->container->set('baseUrl', function($container){
                
                $settings = $container['settings']['app'];
                if ($settings['url'] == '') {

                    $uri       = $container['request']->getUri();
                    $scheme    = $uri->getScheme();
                    $authority = $uri->getAuthority();
                    $baseUrl   = ($scheme ? $scheme . ':' : '').($authority ? '//' . $authority : '');
                
                } else {
                    $baseUrl = $settings['url'];
                }
                return $baseUrl;

            });
        }
    }