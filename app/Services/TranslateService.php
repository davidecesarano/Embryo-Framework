<?php 

    /**
     * TranslateService 
     */

    namespace App\Services;

    use Embryo\Translate\Translate;
    use Embryo\Container\ServiceProvider;

    class TranslateService extends ServiceProvider
    {   
        /**
         * Registers service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->container->set('translate', function($container){
                $translate = $container->get('settings')['translate'];
                return (new Translate($translate['languagePath']))->setMessages();
            });
        }
    }