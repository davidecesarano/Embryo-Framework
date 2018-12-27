<?php 

    /**
     * SessionService
     */

    namespace App\Services;

    use Embryo\Session\Session;
    use Embryo\Container\ServiceProvider;
    
    class SessionService extends ServiceProvider
    {
        /**
         * Registers service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->container->set('session', function(){
                return new Session;
            });
        }
    }