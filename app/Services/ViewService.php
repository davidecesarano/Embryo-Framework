<?php 

    /**
     * ViewService
     */

    namespace App\Services;

    use Embryo\Container\ServiceProvider;
    use Embryo\View\View;
    
    class ViewService extends ServiceProvider
    {
        /**
         * Registers service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->container->set(View::class, function($container){
                $settings = $container->get('settings');
                return new View($settings['view']['templatePath'], $settings['view']['compilerPath']);
            });

            // alias
            $this->container->set('view', function($container){
                return $container[View::class];
            });
        }
    }