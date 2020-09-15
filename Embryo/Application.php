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
    
    use Embryo\Container\Container;
    use Embryo\Http\Emitter\Emitter;
    use Embryo\Http\Factory\ResponseFactory;
    use Embryo\Routing\Exceptions\{NotFoundException, MethodNotAllowed};
    use Embryo\Routing\Middleware\{MethodOverrideMiddleware, RoutingMiddleware, RequestHandlerMiddleware};
    use Psr\Container\ContainerInterface;
    use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
    
    class Application 
    {
        /**
         * @var ContainerInterface $container
         */
        private $container;
        
        /**
         * Set container and application boot.
         * 
         * @param array $settings
         */
        public function __construct(array $settings = [])
        {
            $this->container = new Container;
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
            $this->container->set('settings', function () use ($settings) {
                return $settings;
            });

            $services = new Services($this->container);
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
         * @return ContainerInterface
         */
        public function getContainer(): ContainerInterface
        {
            return $this->container;
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
                $service = new $service($this->container);
                $service->register();
            }

            if (is_callable($service)) {
                call_user_func($service, $this->container);
            }

            if (is_array($service)) {
                foreach ($service as $s) {
                    if (is_callable($s)) {
                        call_user_func($s, $this->container);        
                    } else if (is_string($s)) {
                        $s = new $s($this->container);
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
            $this->container['requestHandler']->add($middleware);
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
         */
        public function get($pattern, $callback)
        {
            return $this->container['router']->get($pattern, $callback);
        }

        /**
         * POST
         *
         * @param string $pattern
         * @param mixed $callback
         */
        public function post($pattern, $callback)
        {
            return $this->container['router']->post($pattern, $callback);
        }
        
        /**
         * PUT
         *
         * @param string $pattern
         * @param mixed $callback
         */
        public function put($pattern, $callback)
        {
            return $this->container['router']->put($pattern, $callback);
        }

        /**
         * PATCH
         *
         * @param string $pattern
         * @param mixed $callback
         */
        public function patch($pattern, $callback)
        {
            return $this->container['router']->patch($pattern, $callback);
        }
        
        /**
         * DELETE
         *
         * @param string $pattern
         * @param mixed $callback
         */
        public function delete($pattern, $callback)
        {
            return $this->container['router']->delete($pattern, $callback);
        }

        /**
         * OPTIONS
         *
         * @param string $pattern
         * @param mixed $callback
         */
        public function options($pattern, $callback)
        {
            return $this->container['router']->options($pattern, $callback);
        }

        /**
         * MAP
         *
         * @param array $methdos
         * @param string $pattern
         * @param mixed $callback
         */
        public function map(array $methods, $pattern, $callback)
        {
            return $this->container['router']->map($methods, $pattern, $callback);
        }

        /**
         * ALL
         *
         * @param string $pattern
         * @param mixed $callback
         */
        public function all($pattern, $callback)
        {
            return $this->container['router']->all($pattern, $callback);     
        }

        /**
         * PREFIX
         *
         * @param string $prefix
         */
        public function prefix($prefix)
        {
            return $this->container['router']->prefix($prefix);     
        }

        /**
         * MIDDLEWARE
         *
         * @param string|array|MiddlewareInterface $middleware
         */
        public function middleware($middleware)
        {
            return $this->container['router']->middleware($middleware);     
        }

        /**
         * GROUP
         *
         * @param callable $callback
         */
        public function group($callback)
        {
            return $this->container['router']->group($callback);     
        }

        /**
         * REDIRECT
         *
         * @param string $pattern
         * @param string $location
         * @param int $code
         */
        public function redirect($pattern, $location, $code = 302)
        {
            return $this->container['router']->redirect($pattern, $location, $code);     
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
            $this->addMiddleware(new RoutingMiddleware($this->container['router']));
            $this->addMiddleware(new RequestHandlerMiddleware($this->container));        

            $request  = $this->container['request'];
            $response = $this->container['response'];
            $response = $this->container['requestHandler']->dispatch($request, $response);
            $emitter  = new Emitter;
            $emitter->emit($response);
        }
    }