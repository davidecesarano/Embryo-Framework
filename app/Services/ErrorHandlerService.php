<?php 

    /**
     * ErrorHandlerService
     */

    namespace App\Services;

    use Embryo\Container\ServiceProvider;
    use Embryo\Error\ErrorHandler;
    
    class ErrorHandlerService extends ServiceProvider
    {
        /**
         * Registers service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->container->set('errorHandler', function($container){
                
                $settings = $container->get('settings');
                $logger   = $container->get('logger');
                $errors   = $settings['errors'];
                
                $error = new ErrorHandler($errors['displayDetails'], $errors['logErrors']);
                $error->setLogger($logger);
                return $error;
                
            });
        }
    }