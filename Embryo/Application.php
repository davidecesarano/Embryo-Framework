<?php 
    
    /**
     * Application
     * 
     * Embryo Framework kernel.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link https://github.com/davidecesarano/embryo-framework 
     */

    namespace Embryo;
    
    use Embryo\Container\ContainerBuilder;
    use Embryo\Container\Interfaces\ContainerBuilderInterface;
    use Embryo\Error\Interfaces\ErrorRendererInterface;
    use Embryo\Error\Middleware\ErrorHandlerMiddleware;
    use Embryo\Http\Emitter\Emitter;
    use Embryo\Routing\Middleware\{MethodOverrideMiddleware, RoutingMiddleware, RequestHandlerMiddleware};
    use Embryo\Routing\Interfaces\{RouteInterface, RouterInterface};
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Server\MiddlewareInterface;
    use Throwable;
    
    class Application 
    {
        /**
         * @var ContainerBuilderInterface $containerBuilder
         */
        private $containerBuilder;

        /**
         * @var bool $internalApplicationError
         */
        private $internalApplicationError = false;

        /**
         * @var bool $errorMiddlewareAdded
         */
        private $errorMiddlewareAdded = false;
        
        /**
         * Set container and application boot.
         * 
         * @param array $settings
         */
        public function __construct(array $settings = [])
        {
            $this->containerBuilder = new ContainerBuilder;
            $this->bootstrap($settings);
        }

        /**
         * Boot application.
         *
         * @param array $settings
         * @return void
         */
        private function bootstrap(array $settings = [])
        {
            $this->containerBuilder->set('settings', function() use($settings) {
                return $settings;
            });

            $services = new Services($this->containerBuilder);
            $services->register();
        }

        /**
         * Include application file.
         * 
         * @param string|array $files
         * @return void
         * @throws \RuntimeException
         * @throws \InvalidArgumentException
         * @throws \Exception
         */
        public function import($files): void 
        {
            try {

                if (is_string($files)) {
                    
                    if (file_exists($files)) {
                        $closure = function($app) use($files) {
                            return include $files;
                        };
                        call_user_func($closure, $this);
                    } else {
                        throw new \RuntimeException("File $files does not exists");
                    }

                } elseif (is_array($files)) {
                    
                    foreach ($files as $file) {
                        if (file_exists($file)) {
                            $closure = function($app) use($file) {
                                return include $file;
                            };
                            call_user_func($closure, $this);
                        } else {
                            throw new \RuntimeException("File $file does not exists");
                        }
                    } 

                } else {
                    throw new \InvalidArgumentException("Files must be string or array");
                }

                Facade::init($this->containerBuilder);
                
            } catch (Throwable $e) {
                $this->throwApplicationError($e);
            }
        }

        /**
         * Throw internal application error.
         * 
         * @param Throwable $error 
         * @return mixed
         */
        private function throwApplicationError(Throwable $error) 
        {
            $errorHandler = $this->containerBuilder->get('errorHandler');
            $request      = $this->containerBuilder->get('request');
            $response     = $errorHandler->process($request, $error);

            $this->internalApplicationError = true;
            return $this->emit($response);
        }
        
        /**
         * ------------------------------------------------------------
         * CONTAINER
         * ------------------------------------------------------------
         */

        /**
         * Return container.
         * 
         * @return ContainerBuilderInterface
         */
        public function getContainer(): ContainerBuilderInterface
        {
            return $this->containerBuilder;
        }

        /**
         * ------------------------------------------------------------
         * SERVICES
         * ------------------------------------------------------------
         */

        /**
         * Add service.
         * 
         * @param array|string|callable $service
         * @return void
         * @throws \InvalidArgumentException
         */
        public function service($service)
        {
            if(!is_string($service) && !is_callable($service) && !is_array($service)) {
                throw new \InvalidArgumentException('Service must be a string, array or callable');
            }

            if (is_string($service)) {
                $service = new $service($this->containerBuilder);
                $service->register();
            }

            if (is_callable($service)) {
                call_user_func($service, $this->containerBuilder);
            }

            if (is_array($service)) {
                foreach ($service as $s) {
                    if (is_callable($s)) {
                        call_user_func($s, $this->containerBuilder);        
                    } else if (is_string($s)) {
                        $s = new $s($this->containerBuilder);
                        $s->register();
                    } else {
                        throw new \InvalidArgumentException('Service must be a string or callable in array services');
                    }
                }
            }

            Facade::init($this->containerBuilder);
        }

        /**
         * ------------------------------------------------------------
         * MIDDLEWARE
         * ------------------------------------------------------------
         */

        /**
         * Add middleware.
         *
         * @param string|MiddlewareInterface $middleware
         * @return void 
         */
        public function addMiddleware($middleware)
        {
            $this->containerBuilder->get('requestHandler')->add($middleware);
        }

        /**
         * Add error handler middleware.
         * 
         * It's possible prepend a custom error 
         * handler middleware.
         *
         * @param string|ErrorRendererInterface $renderer
         * @return void 
         */
        public function addErrorMiddleware($renderer = null)
        {
            if ($renderer && !is_string($renderer) && !$renderer instanceof ErrorRendererInterface) {
                throw new \InvalidArgumentException('Error Renderer must be a string or an instance of ErrorRendererInterface');
            }

            $errorRenderer  = is_string($renderer) ? new $renderer : $renderer;
            $requestHandler = $this->containerBuilder->get('requestHandler');
            $errorHandler   = $this->containerBuilder->get('errorHandler')->setRenderer($errorRenderer);
            $middleware     = (new ErrorHandlerMiddleware)->setErrorHandler($errorHandler);
            
            $requestHandler->prepend($middleware);
            $this->errorMiddlewareAdded = true;
        }

        /**
         * Add routing middlewares.
         * 
         * @return void
         */
        private function addRoutingMiddleware() 
        {
            $this->addMiddleware(new MethodOverrideMiddleware);
            $this->addMiddleware(new RoutingMiddleware($this->containerBuilder->get('router')));
            $this->addMiddleware(new RequestHandlerMiddleware($this->containerBuilder)); 
        }

        /**
         * ------------------------------------------------------------
         * ROUTING
         * ------------------------------------------------------------
         */

        /**
         * GET
         *
         * @param string $pattern
         * @param mixed $callback
         * @return RouteInterface
         */
        public function get($pattern, $callback): RouteInterface
        {
            return $this->containerBuilder->get('router')->get($pattern, $callback);
        }

        /**
         * POST
         *
         * @param string $pattern
         * @param mixed $callback
         * @return RouteInterface
         */
        public function post($pattern, $callback): RouteInterface
        {
            return $this->containerBuilder->get('router')->post($pattern, $callback);
        }
        
        /**
         * PUT
         *
         * @param string $pattern
         * @param mixed $callback
         * @return RouteInterface
         */
        public function put($pattern, $callback): RouteInterface
        {
            return $this->containerBuilder->get('router')->put($pattern, $callback);
        }

        /**
         * PATCH
         *
         * @param string $pattern
         * @param mixed $callback
         * @return RouteInterface
         */
        public function patch($pattern, $callback): RouteInterface
        {
            return $this->containerBuilder->get('router')->patch($pattern, $callback);
        }
        
        /**
         * DELETE
         *
         * @param string $pattern
         * @param mixed $callback
         * @return RouteInterface
         */
        public function delete($pattern, $callback): RouteInterface
        {
            return $this->containerBuilder->get('router')->delete($pattern, $callback);
        }

        /**
         * OPTIONS
         *
         * @param string $pattern
         * @param mixed $callback
         * @return RouteInterface
         */
        public function options($pattern, $callback): RouteInterface
        {
            return $this->containerBuilder->get('router')->options($pattern, $callback);
        }

        /**
         * MAP
         *
         * @param array $methods
         * @param string $pattern
         * @param mixed $callback
         * @return RouteInterface
         */
        public function map(array $methods, $pattern, $callback): RouteInterface
        {
            return $this->containerBuilder->get('router')->map($methods, $pattern, $callback);
        }

        /**
         * ALL
         *
         * @param string $pattern
         * @param mixed $callback
         * @return RouteInterface
         */
        public function all($pattern, $callback): RouteInterface
        {
            return $this->containerBuilder->get('router')->all($pattern, $callback);     
        }

        /**
         * PREFIX
         *
         * @param string $prefix
         * @return RouterInterface
         */
        public function prefix($prefix): RouterInterface
        {
            return $this->containerBuilder->get('router')->prefix($prefix);     
        }

        /**
         * MIDDLEWARE
         *
         * @param string|array|MiddlewareInterface $middleware
         * @return RouterInterface
         */
        public function middleware($middleware): RouterInterface
        {
            return $this->containerBuilder->get('router')->middleware($middleware);     
        }

        /**
         * GROUP
         *
         * @param callable $callback
         * @return RouterInterface
         */
        public function group($callback): RouterInterface
        {
            return $this->containerBuilder->get('router')->group($callback);     
        }

        /**
         * REDIRECT
         *
         * @param string $pattern
         * @param string $location
         * @param int $code
         * @return mixed
         */
        public function redirect($pattern, $location, $code = 302)
        {
            return $this->containerBuilder->get('router')->redirect($pattern, $location, $code);     
        }

        /**
         * ------------------------------------------------------------
         * RUNNING
         * ------------------------------------------------------------
         */

        /**
         * Emit response.
         * 
         * @param ResponseInterface $response 
         * @return mixed
         */
        private function emit(ResponseInterface $response)
        {
            $emitter = new Emitter;
            $emitter->emit($response);
        }

        /**
         * Run application.
         * 
         * @return mixed
         */
        public function run()
        {   
            if (!$this->internalApplicationError) {
                if (!$this->errorMiddlewareAdded) {
                    $this->addErrorMiddleware();
                }
                $this->addRoutingMiddleware();

                $request  = $this->containerBuilder->get('request');
                $response = $this->containerBuilder->get('response');
                $response = $this->containerBuilder->get('requestHandler')->dispatch($request, $response);
                
                return $this->emit($response);
            }
        }
    }