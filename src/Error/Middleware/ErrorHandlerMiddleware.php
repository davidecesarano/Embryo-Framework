<?php 

    /**
     * ErrorHandlerMiddleware
     */

    namespace Embryo\Error\Middleware;

    use Embryo\Error\ErrorHandler;
    use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
    use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};

    class ErrorHandlerMiddleware implements MiddlewareInterface
    {

        private $handler;

        public function setHandler($handler)
        {
            $this->handler = $handler;
            return $this;
        }

        public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface 
        {
            try {
                
                $response = $handler->handle($request);                
                if ($response->getStatusCode() >= 400 && $response->getStatusCode() <= 599) {
                    
                    $exception = new \Exception($response->getReasonPhrase(), $response->getStatusCode());
                    return $this->handleError($request, $exception);    

                } else {
                    return $response;
                }
                
            } catch (\Throwable $exception) {
                return $this->handleError($request, $exception);
            }
        }

        /**
         * Execute the error handler.
         * 
         * @param Throwable $exception
         * @return ResponseInterface
         */
        private function handleError(ServerRequestInterface $request, \Throwable $exception): ResponseInterface
        {
            $handler = ($this->handler) ? $this->handler : new ErrorHandler;
            return $handler->process($request, $exception);
        }
    }