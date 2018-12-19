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
            $this->container->set(StreamLogger::class, function($container){
                $settings = $container->get('settings');
                return new StreamLogger($settings['logger']['logPath']);
            });

            $this->container->alias('logger', StreamLogger::class);
        }
    }