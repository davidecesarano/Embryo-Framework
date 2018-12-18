<?php 

    /**
     * MailService
     */

    namespace App\Services;

    use App\Helpers\Mail;
    use Embryo\Container\ServiceProvider;
    
    class MailService extends ServiceProvider
    {
        /**
         * Registers service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->container->set('mail', function($container){
                $settings = $container->get('settings');
                return new Mail($settings['mail']);
            });
        }
    }