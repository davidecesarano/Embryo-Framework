<?php 
    
    /**
     * Application
     * 
     * 
     */

    namespace Embryo\Application;
    
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
         * Bootstrap application
         */
        public function __construct(array $settings = [])
        {
            $this->container = new Container;
            $this->setSettings($settings);
        }
        
        /**
         * ------------------------------------------------------------
         * CONTAINER
         * ------------------------------------------------------------
         */

        /**
         * Returns container.
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
         * Adds service.
         * 
         * @param string $service
         * @return void
         */
        public function service(string $service)
        {
            $service = new $service($this->container);
            $service->register();
        }

        /**
         * ------------------------------------------------------------
         * MIDDLEWARE
         * ------------------------------------------------------------
         */

        /**
         * Adds middleware.
         *
         * @param string|MiddlewareInterface $middleware 
         */
        public function addMiddleware($middleware)
        {
            $this->container['middlewareDispatcher']->add($middleware);
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
         * @param array $middleware
         */
        public function middleware(...$middleware)
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
         * Runs application.
         * 
         * Definisce la request e un funzione con la response. Esegue il
         * dispatcher delle rotte allegando la rotta al dispatcher dei
         * middleware. Esegue ....
         * 
         * @return ResponseInterface
         */
        public function run()
        {
            $request  = $this->container['request'];
            $response = $this->container['response'];
            $response = $this->container['middlewareDispatcher']->dispatch($request, $response);
            $emitter  = new Emitter;
            $emitter->emit($response);
        }

        private function exceptionHandler(Exception $e, ServerRequestInterface $request, ResponseInterface $response)
        {
            $handler = $this->container['exceptionHandler'];
            return call_user_func_array($handler, [$request, $response, $e]);
        }

        /**
         * ------------------------------------------------------------
         * RESPONSE
         * ------------------------------------------------------------
         */
        
        private function sendResponse(ResponseInterface $response)
        {
            // protocollo, stato e frase
            $http_line = sprintf('HTTP/%s %s %s',
                $response->getProtocolVersion(),
                $response->getStatusCode(),
                $response->getReasonPhrase()
            );
            
            // modifica header
            header($http_line, true, $response->getStatusCode());
            foreach ($response->getHeaders() as $name => $values) {
                foreach ($values as $value) {
                    header("$name: $value", false);
                }
            }
            
            // stream
            $stream = $response->getBody();
            if ($stream->isSeekable()) {
                $stream->rewind();
            }
            
            // scrive
            while (!$stream->eof()) {
                echo $stream->read(1024 * 8);
            }
        }    
        
        /**
         * ------------------------------------------------------------
         * BOOT
         * ------------------------------------------------------------
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

        private function setServices()
        {
            $services = $this->container['settings']['services'];
            foreach($services as $service) {
                $this->service($service);
            }
        }

        private function setMiddleware()
        {
            $middlewares = $this->container['settings']['middleware'];
            foreach($middlewares as $middleware) {
                $this->middleware($middleware);
            }
        }

        private function mapRoutes()
        {
            $app = $this;
            $routes = require $this->container['paths']['routes'].'group.php';
            return function ($app) use ($routes) {
                require $routes;
            };
        }
    }