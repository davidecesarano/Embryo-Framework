<?php 

    /**
     * ErrorHandlerMiddleware
     */

    namespace Embryo\Error\Middleware;

    use Embryo\Error\Exceptions\HttpErrorException;
    use Embryo\Error\Handlers\{ExceptionHandler, HttpErrorHandler};
    use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
    use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};

    class ErrorHandlerMiddleware implements MiddlewareInterface
    {
        public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface 
        {
            ob_start();
            $level = ob_get_level();

            try {
                
                $response   = $handler->handle($request);
                $statusCode = $response->getStatusCode();
                if ($statusCode >= 400 && $statusCode < 600) {
                    $exception = new HttpErrorException($response->getReasonPhrase(), $response->getStatusCode());
                    return $this->handleError($request, $exception);
                }
                return $response;
            
            } catch (HttpErrorException $exception) {
                return $this->handleError($request, $exception);
            } catch (\Throwable $exception) {
                return $this->handleError($request, new HttpErrorException('', 500, $exception));
            } finally {
                while (ob_get_level() >= $level) {
                    ob_end_clean();
                }
            }
        }

        /**
         * Execute the error handler.
         */
        private function handleError(ServerRequestInterface $request, HttpErrorException $exception): ResponseInterface
        {
            $request = $request->withAttribute('errorInfo', $exception);
            $handler = new HttpErrorHandler;
            return $handler->handle($request);
        }
    }