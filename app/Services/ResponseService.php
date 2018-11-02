<?php 

    /**
     * ResponseService 
     */

    namespace App\Services;

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
                $response = new ResponseFactory;
                $response = $response->createResponse(200);
                return $response->withHeader('Content-Type', 'text/html; charset=UTF-8');
            });
        }
    }