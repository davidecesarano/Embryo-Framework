<?php 

    /**
     * ValidationService 
     */

    namespace App\Services;

    use Embryo\Container\ServiceProvider;
    use Embryo\Validation\Validation;

    class ValidationService extends ServiceProvider
    {   
        /**
         * Registers service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->container->set(Validation::class, function($container){
                $settings = $container->get('settings');
                $request  = $container->get('request');
                return new Validation($request, $settings['app']['locale']);
            });

            $this->container->alias('validation', Validation::class);
        }
    }