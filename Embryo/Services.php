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

    use Embryo\Container\Interfaces\ContainerBuilderInterface;
    use Embryo\Http\Factory\{ResponseFactory, ServerRequestFactory};
    use Embryo\Http\Server\RequestHandler;
    use Embryo\Routing\Router;

    class Services 
    {
        /**
         * @var ContainerBuilderInterface $containerBuilder
         */
        private $containerBuilder;

        /**
         * Set container.
         *
         * @param ContainerBuilderInterface $containerBuilder
         */
        public function __construct(ContainerBuilderInterface $containerBuilder)
        {
            $this->containerBuilder = $containerBuilder;
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
            $this->containerBuilder->set('request', function(){
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
            $this->containerBuilder->set('response', function(){
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
            $this->containerBuilder->set('requestHandler', function(){
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
            $this->containerBuilder->set('router', function(){
                return new Router;
            });
        }
    }