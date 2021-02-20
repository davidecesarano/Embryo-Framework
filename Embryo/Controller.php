<?php 

    /**
     * Controller
     * 
     * This class extends the routing controller for use it into
     * app controller's.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link https://github.com/davidecesarano/embryo-framework
     */
    
    namespace Embryo;

    use Embryo\Routing\Controller as RoutingController;
    use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
    
    class Controller extends RoutingController 
    {
        /**
         * ServerRequest
         * 
         * @return ServerRequestInterface
         */
        protected function request(): ServerRequestInterface
        {
            return $this->request;
        }

        /**
         * Response
         * 
         * @return ResponseInterface
         */
        protected function response(): ResponseInterface
        {
            return $this->response;
        }
    }