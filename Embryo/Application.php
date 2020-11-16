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
    use Embryo\Http\Emitter\Emitter;
    use Embryo\Http\Factory\ResponseFactory;
    use Embryo\Routing\Middleware\{MethodOverrideMiddleware, RoutingMiddleware, RequestHandlerMiddleware};
    use Embryo\Routing\Interfaces\{RouteInterface, RouterInterface};
    use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
    use Psr\Http\Server\MiddlewareInterface;
    
    class Application 
    {
        /**
         * @var ContainerBuilderInterface $containerBuilder
         */
        private $containerBuilder;
        
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
         * SERVICE
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
         * ------------------------------------------------------------
         * ROUTING PROXY
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
         * Run application.
         * 
         * @return mixed
         */
        public function run()
        {
            $this->addMiddleware(new MethodOverrideMiddleware);
            $this->addMiddleware(new RoutingMiddleware($this->containerBuilder->get('router')));
            $this->addMiddleware(new RequestHandlerMiddleware($this->containerBuilder));        

            $request  = $this->containerBuilder->get('request');
            $response = $this->containerBuilder->get('response');
            $response = $this->containerBuilder->get('requestHandler')->dispatch($request, $response);
            $emitter  = new Emitter;
            $emitter->emit($response);
        }
    }