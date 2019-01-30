<?php 
    
    /**
     * Application
     * 
     * Embryo Framework kernel.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link https://github.com/davidecesarano/embryo-application 
     */

    namespace Embryo;
    
    use Embryo\Container\Container;
    use Embryo\Http\Emitter\Emitter;
    use Psr\Container\ContainerInterface;
    use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
    use Psr\Http\Server\MiddlewareInterface;
    
    class Application 
    {
        /**
         * @var ContainerInterface $container
         */
        private $container;

        /**
         * @var array $settings 
         */
        private $settings = [];
        
        /**
         * Bootstrap application.
         * 
         * @param array $settings
         */
        public function __construct(array $settings = [])
        {
            $this->container = new Container;
            $this->setSettings($settings);
        }

        /**
         * Set settings application.
         *
         * @param array $settings
         * @return void
         */
        private function setSettings(array  $settings = [])
        {
            if (!empty($settings)) {
                if (!$this->container->has('settings')) {
                    $this->container->set('settings', function () use ($settings) {
                        return $settings;
                    });
                }
            }
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
         * @param string|callable $service
         * @return void
         * @throws InvalidArgumentException
         */
        public function service($service)
        {
            if(!is_string($service) && !is_callable($service)) {
                throw new \InvalidArgumentException('Service must be a string or callable.');
            }

            if (is_string($service)) {
                $service = new $service($this->container);
                $service->register();
            }
            
            if (is_callable($service)) {
                call_user_func($service, $this->container);
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
            $request  = $this->container['request'];
            $response = $this->container['response'];
            $response = $this->container['router']->dispatch($request, $response);
            $emitter  = new Emitter;
            $emitter->emit($response);
        }
    }