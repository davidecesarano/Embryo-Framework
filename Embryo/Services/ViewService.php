<?php 

    /**
     * ViewService
     */

    namespace Embryo\Services;

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
                $view = new View($settings['view']['templatePath'], $settings['view']['compilerPath']);
                return $view->setExtension('embryo');
            });

            $this->container->alias('view', View::class);
        }
    }