<?php 

    /**
     * LoggerService
     */

    namespace App\Services;

    use Embryo\Container\ServiceProvider;
    use Embryo\Log\StreamLogger;
    
    class LoggerService extends ServiceProvider
    {
        /**
         * Registers service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->container->set('logger', function($container){
                $settings = $container->get('settings');
                return new StreamLogger($settings['logger']['logPath']);
            });
        }
    }