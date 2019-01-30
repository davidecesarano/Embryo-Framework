<?php 

    /**
     * ResponseService 
     */

    namespace Embryo\Services;

    use Embryo\Container\ServiceProvider;
    use Embryo\Http\Factory\ResponseFactory;

    class ResponseService extends ServiceProvider
    {   
        /**
         * Registers service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->container->set('response', function(){
                return (new ResponseFactory)->createResponse(200);
            });
        }
    }