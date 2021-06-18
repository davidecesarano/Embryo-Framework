<?php 

    /**
     * ResponseService 
     */

    namespace Embryo\Services;

    use Embryo\Container\ServiceProvider;
    use Embryo\Http\Factory\ResponseFactory;
    use Embryo\Http\Message\Response;

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
                $response = (new ResponseFactory)->createResponse(200);
                return $response->withHeader('Content-Type', 'text/html; charset=UTF-8');
            });

            $this->container->alias(Response::class, 'response');
        }
    }