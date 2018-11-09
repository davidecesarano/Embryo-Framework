<?php 

    namespace Embryo\Error\Traits;

    trait ErrorLogTrait
    {
        /**
         * Write log.
         * 
         * If client error it saves a error log,
         * otherwise it saves a critical error.
         *
         * @param ServerRequestInterface $request
         * @param ResponseInterface $response
         * @param Exception $exception
         * @return void
         */
        protected function log($request, $response, $exception)
        {
            $message = sprintf('[{code}] [{method}] [{path}] %s: %s in %s on line %d',
                get_class($exception),
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            );
            
            $code   = $response->getStatusCode();
            $method = $request->getMethod();
            $path   = $request->getUri()->getPath();
            
            if ($code >= 400 && $code <= 451) {

                $this->logger->error($message, [
                    'code' => $code,
                    'method' => $method,
                    'path' => $path
                ]);

            } else {

                $this->logger->critical($message, [
                    'code' => $code,
                    'method' => $method,
                    'path' => $path
                ]);

            }
        }
    }
    