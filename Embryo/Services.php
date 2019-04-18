<?php 

    /**
     * Services
     * 
     * Embryo's default Service Providers.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link https://github.com/davidecesarano/embryo-framework 
     */

    namespace Embryo;

    use Embryo\Http\Factory\{ResponseFactory, ServerRequestFactory};
    use Embryo\Http\Server\RequestHandler;
    use Embryo\Routing\Router;
    use Psr\Container\ContainerInterface;

    class Services 
    {
        /**
         * @var ContainerInterface $container
         */
        private $container;

        /**
         * Set container.
         *
         * @param ContainerInterface $container
         */
        public function __construct(ContainerInterface $container)
        {
            $this->container = $container;
        }

        /**
         * Register Service Providers.
         *
         * @return void
         */
        public function register()
        {
            $this->setRequestService();
            $this->setResponseService();
            $this->setRequestHandlerService();
            $this->setRouterService();
        }

        /**
         * Set PSR Request Service.
         *
         * @return void
         */
        private function setRequestService()
        {
            $this->container->set('request', function(){
                return (new ServerRequestFactory)->createServerRequestFromServer();
            });
        }

        /**
         * Set PSR Response Service.
         *
         * @return void
         */
        private function setResponseService()
        {
            $this->container->set('response', function(){
                return (new ResponseFactory)->createResponse(200);
            });
        }

        /**
         * Set PSR RequestHandler Service.
         *
         * @return void
         */
        private function setRequestHandlerService()
        {
            $this->container->set('requestHandler', function(){
                return new RequestHandler;
            });
        }

        /**
         * Set Router Service.
         *
         * @return void
         */
        private function setRouterService()
        {
            $this->container->set('router', function(){
                return new Router;
            });
        }
    }