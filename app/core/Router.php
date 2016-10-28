<?php namespace Core;
	
	/**
	 * Router 
	 *
	 * Consente la creazione di percorsi url attraverso i quali è
	 * possibile invocare una chiusura (funzione) o un controller.
	 *
	 * @author Davide Cesarano
	 */
	
	use \Exception;
    use Core\Config;
    use Helpers\Request;
	
	class Router {
		
		/**
		 * Pattern dei filtri della
		 * richiesta
		 *
		 * @var array $filters
		 */
		private $filters = array(
			':url' => '([A-Za-z0-9-\/_]+)',
			':id'  => '([0-9]+)'
		);
		
		/**
		 * Array delle istruzioni
		 *
		 * @var array $routes
		 */
		private $routes = array();
		
		/**
		 * Array delle azioni da svolgere: possono 
		 * invocare una chiusura o un oggetto
		 *
		 * @var array $callback
		 */
		private $callback = array();
		
        /**
         * Array dei metodi delle
         * richieste HTTP 
         *
         * @var array $methods 
         */
        private $methods = array();
        
        /**
         * Array dei gruppi delle 
         * istruzioni
         *
         * @var array $groups 
         */
        private $groups = array();
        
        /**
         * Array dei middleware delle
         * istruzioni
         *
         * @var array $middlewares 
         */
        private $middlewares = array();
        
		/**
		 * Segnala istruzione trovata
         * per la specifica richiesta HTTP
		 *
		 * @var boolena $found
		 */
		private $found = false;
        
        /**
		 * Parametri da passare al metodo
         * ottenuti tramite i filtri delle
         * istruzioni
		 *
		 * @var array $params 
		 */
		private $params = array();
		
		/**
		 * Controller 
		 *
		 * @var string $controller 
		 */
		private $controller;
        
        /**
         * Crea route con metodo richiesta
         * HTTP di tipo POST
         *
         * @param string $route
		 * @param string $callback
		 * @return this
         */
        public function post($route, $callback){
           $this->add('POST', $route, $callback);
        }
        
        /**
         * Crea route con metodo richiesta
         * HTTP di tipo GET
         *
         * @param string $route
		 * @param string $callback
		 * @return this
         */
        public function get($route, $callback){
            $this->add('GET', $route, $callback);
        }
        
        /**
         * Crea route con metodo richiesta
         * HTTP di tipo POST o GET
         *
         * @param string $route
		 * @param string $callback
		 * @return this
         */
        public function any($route, $callback){
           $this->add('ANY', $route, $callback);            
        }
        
        /**
         * Crea route con metodo richiesta
         * HTTP di tipo POST e AJAX
         *
         * @param string $route
		 * @param string $callback
		 * @return this
         */
        public function ajaxPost($route, $callback){
           $this->add('AJAXPOST', $route, $callback);            
        }
        
        /**
         * Crea route con metodo richiesta
         * HTTP di tipo GET e AJAX
         *
         * @param string $route
		 * @param string $callback
		 * @return this
         */
        public function ajaxGet($route, $callback){
           $this->add('AJAXGET', $route, $callback);            
        }
        
        /**
		 * Crea route
		 *
		 * @param string $route
		 * @param string $callback
		 * @return this
		 */
		public function add($method, $route, $callback){
			
            $this->method($method);
			$this->route($route);
			$this->callback($callback);
		
        }
        
        /**
		 * Crea un gruppo di istruzioni
		 *
		 * @param string $prefix
		 * @param array $callback
		 * @return array $this->groups
		 */
		public function group($prefix, $callback){
			
            // crea prefisso
            array_push($this->groups, $prefix);
            
            // presenza middleware
            if(is_array($callback)){
                
                // assegna middleware
                array_push($this->middlewares, $callback['middleware']);
                
                // invoca gruppo
                call_user_func($callback[0]);
                
                // ripristina route successivi
                array_pop($this->middlewares);
                
            }else{
                
                // assenza middleware
                call_user_func($callback);
            
            }
            
            // ripristina route successivi
            array_pop($this->groups);
			
		}
        
        /**
		 * Metodo richiesta HTTP
		 *
		 * @param string $method
		 * @return this
		 */
        public function method($method){
            $this->methods[] = $method;
        }
        
        /**
		 * Istruzione
		 *
		 * @param string $route 
		 * @param array $this->groups 
		 * @return array $this->routes
		 * @throws exception
		 */
		public function route($route){
			
            // verifica formato
			if(preg_match('/^([A-Za-z0-9-\/._:]+)$/', $route) || $route == ''){
                
                // verifica se l'istruzione è stata inserita in un gruppo
                if(!empty($this->groups)){
                    
                    // assegna un prefisso all'istruzione
                    foreach($this->groups as $group){
                        $route = ($route == '') ? $group : $group.'/'.$route;
                    }
                
                }
                $this->routes[] = ($route == '') ? 'index' : $route;
                
            }else{
				throw new Exception("Formato route <strong>$route</strong> non compatibile!");
			}
			
		}
        
        /**
         * Risposta 
         *
         * @param array $this->middlewares
         * @param mixed $callback 
         */
        public function callback($callback){
            
            // verifica se l'istruzione contiene middleware
            if(!empty($this->middlewares)){
                
                // assegna middleware alla risposta
                foreach($this->middlewares as $middleware){
                    $callback = ['middleware' => $middleware, $callback];
                }
                
            }
            $this->callback[] = $callback;
   
        }

		/**
		 * Ottiene URI
		 *
		 * @param array $_GET['url']
		 * @return string
		 */
		public function request(){
			return (isset($_GET['url'])) ? filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL) : 'index';
		}
		
		/**
		 * Esegue route
		 *
		 * @param array $this->request
		 * @param array $this->routes
		 * @return mixed
		 * @throws exception
		 */
		public function execute(){	
            
			// trova l'istruzione per la richiesta
			foreach($this->routes as $key => $route){
				
				// se l'istruzione è stato trovata...
				if(preg_match($this->getPattern($route), $this->request())){
					
					// segnala route trovato
					$this->found = true;
					
					// array URI
					$request_array = explode('/', $this->request());
					
					// array route 
					$route_array = explode('/', $route);
                    
                    // verifica metodo richiesta HTTP
                    $this->checkMethod($this->methods[$key]);
                    
					// conversione filtri
                    $this->convertFilter($route_array, $request_array);

					// invoca una chiusura o un oggetto
					$this->invoke($this->callback[$key]);
					
					// ferma la ricerca
					break;
					
				}
				
			}
			
			// se il route non esiste genera errore
			$this->error();
			
		}
        
        /**
         * Verifica il metodo della richiesta HTTP 
         *
         * @param string $method
         */
        public function checkMethod($method){
            
            switch($method){
                case 'GET':
                    if(!Request::isGet()) $this->error404('Metodo della richiesta non valido!');
                    break;
                case 'AJAXGET':
                    if(!Request::isGet() && !Request::isAjax()) $this->error404('Metodo della richiesta non valido!');
                    break;
                case 'POST':
                    if(!Request::isPost()) $this->error404('Metodo della richiesta non valido!');
                    break;
                case 'AJAXPOST':
                    if(!Request::isPost() && !Request::isAjax()) $this->error404('Metodo della richiesta non valido!');
                    break;
                case 'ANY':
                    if(!Request::isPost() && !Request::isGet()) $this->error404('Metodo della richiesta non valido!');
                    break; 
            }
            
        }
        
        /**
         * Converte filtri delle istruzioni
         *
         * @param array $route_array
         * @param array $request_array
         */
        public function convertFilter($route_array = array(), $request_array = array()){
            
            // ricava filtro/i
            foreach($route_array as $position => $filter){
                
                // converte filtro
                switch($filter){
                    case ':url':
                    case ':id':
                        $this->setParam($route_array, $request_array, $position);
                        break;
                }
                
            }
            
        }
        
        /**
         * Invoca una closure 
         * o un controller
         *
         * @param mixed $callback
         */
        public function invoke($callback){
            
            /**
             * Controller 
             */
            if(is_string($callback)){
                
                /**
                 * Invoca controller e azione (ed, eventualmente,
                 * dei parametri)
                 *
                 * @example $router->get('test', 'Test@index');
                 * @example $router->get('test/:id', 'Test@action');
                 * @example $router->group('test_1', function() use($router){
                 *     $router->get('test_2', 'Test@index');
                 * });
                 */
                $this->getController($callback); 
            
            /**
             * Middleware 
             */
            }elseif(is_array($callback)){
                
                /**
                 * Singolo con controller
                 */
                if(is_string($callback[0])){
                    
                    /**
                     * Invoca middleware, controller e azione (ed, eventualmente,
                     * dei parametri)
                     *
                     * @example $router->get('test', ['middleware' => 'Test', 'Test@index']);
                     */
                    $this->getMiddleware($callback['middleware']);
                    $this->getController($callback[0]);
                
                /**
                 * Gruppo 
                 */
                }elseif(is_array($callback[0])){
                    
                    /**
                     * Con controller
                     */
                    if(is_string($callback[0][0])){

                        /**
                         * Invoca middleware di gruppo, middleware singolo,
                         * controller, azione (ed, eventualmente, dei parametri)
                         *
                         * @example $router->group('test_1', ['middleware' => 'Test_1', function() use($router){
                         *     $router->get('test_2', ['middleware' => 'Test_2', 'Test@index']);
                         * }]);
                         */
                        $this->getMiddleware($callback['middleware'], $callback[0]['middleware']);
                        $this->getController($callback[0][0]);
                    
                    /**
                     * Con closure
                     */
                    }else{
                        
                        /**
                         * Invoca middleware di gruppo, middleware singolo,
                         * controller, azione (ed, eventualmente, dei parametri)
                         *
                         * @example $router->group('test_1', ['middleware' => 'Test_1', function() use($router){
                         *     $router->get('test_2', ['middleware' => 'Test_2', function(){ echo 'test'; }]);
                         * }]);
                         */
                        $this->getMiddleware($callback['middleware'], $callback[0]['middleware']);
                        $this->getClosure($callback[0][0]);
                        
                    }
                
                /**
                 * Singolo con funzione
                 */                
                }else{ 
                    
                    /**
                     * Invoca middleware e funzione 
                     *
                     * @example $router->get('test', ['middleware' => 'Test', function(){ echo 'test'; }]);
                     */
                    $this->getMiddleware($callback['middleware']);
                    $this->getClosure($callback[0]);
                
                }
            
            /**
             * Closure (funzione)
             */
            }else{
                
                /**
                 * Invoca una funzione 
                 *
                 * @example $router->get('test', function(){ echo 'test'; });
                 * @example $router->get('test/:id', function($param){ echo $param; });
                 */
                $this->getClosure($callback);
            
            }
           
        }
        
        /**
		 * Invoca un controller 
		 *
		 * @example $router->add('test', 'class@method');
		 * @param string $callback
		 * @param array $this->params
		 */
		public function getController($callback){
			
            // controller e action
            $controller = explode('@', $callback);
            $class = $controller[0];
			$method = $controller[1];
			
			// carica classe controller
			$this->getClassController($class);
			
            // carica method action
			$this->getAction($method, $this->params);
			
		}
		
		/**
		 * Invoca una closure (funzione)
		 *
		 * @example $router->add('test', function($param){ echo $param; });
		 * @param $callback
		 * @param array $this->params
		 */
		public function getClosure($callback){

            if(!empty($this->params)){
				
				// presenza di parametri
				call_user_func_array($callback, $this->params);
				return;
				
			}else{
				
				// assenza di parametri
				call_user_func($callback);
			
			}
		
		}
        
        /**
         * Invoca un middleware 
         *
         * @param int|string $value
         * @param array $this->callback 
         * @throws exception 
         */
        public function getMiddleware($general, $group = null){
            
            // middleware generale
            $middleware_array = (is_array($general)) ? $general : array($general);
            
            // routes middleware nel file core/Config.php
            $middlewares = Config::all('middlewares');
            
            // gruppo di middleware
            if($group && is_array($group)){
                
                // in gruppo di route
                foreach($group as $value){
                    array_push($middleware_array, $value);
                }
            
            }elseif($group && is_string($group)){
                
                // in route singolo
                array_push($middleware_array, $group);
                
            }

            // invoca
            if(is_array($middleware_array)){
                
                foreach($middleware_array as $name){
                   
                    // ricava classe middleware e azione
                    $middleware = explode('@', $middlewares[$name]);
                    $class = $middleware[0];
                    $method = $middleware[1];
                    
                    // carica classe middleware e metodo
                    $this->getClassMiddleware($class, $method);
                    
                }
            
            }else{
                throw new Exception("Formato middleware non valido!");
            }
            
        }
        
        /**
		 * Assegna parametri 
		 *
		 * @param array $route_array 
		 * @param array $request_array
		 * @param int $position
		 * @return array $this->params
		 */
		public function setParam($route_array, $request_array, $position){ 
            
            // ultimo filtro
            $last_value_route_array = end($route_array);
            $last_key_route_array = key($route_array);

            // se l'ultimo filtro è di tipo :url...
            if($last_value_route_array == $route_array[$position] && $last_key_route_array == $position){
                
                // unisce array richiesta 
                $implode = '';
                for($i=$position; $i<count($request_array); $i++){
                    $implode .= $request_array[$i].'/';
                }
                $url = rtrim($implode, '/');
                
                // inserisce parametro
                array_push($this->params, $url);
                
			}else{
                
                // inserisce paramentro
                array_push($this->params, $request_array[$position]);
			
			}
            
		}

        /**
		 * Carica il controller
		 *
		 * @param string $name
		 * @return object $this->controller
		 * @throws exception
		 */
		public function getClassController($name){
			
            // verifica presenza namespace
            if(strstr($name, '\\')){ 
                
                $exp = explode('\\', $name);
                $controller = '';
                foreach($exp as $key => $value){
                    $controller .= ucfirst($value).'\\';
                }
                $controller = rtrim($controller, '\\');
                
            }else{
                $controller = ucfirst($name);
            }
            
			// classe
			$class = 'Controllers\\'.$controller;
			
			// carica classe se esiste
			if(class_exists($class)){
				$this->controller = new $class;
			}else{
				throw new Exception("Il controller <strong>$class</strong> non esiste!");					
			}			
			
		}
		
		/**
		 * Carica l'azione e il parametro
		 *
		 * @param string $action
		 * @param array|null $params
		 * @return object $this->controller->{$action}($param_1, $param_2, ...)
		 * @throws exception
		 */
		public function getAction($action, $params = null){
			
			// carica metodo (azione) se esiste
			if(method_exists($this->controller, $action)){
				
                // se i parametri non esistono...
                if(empty($this->params)){
                    
                    // carica solo metodo
                   call_user_func(array($this->controller, $action));
                    
                }else{
                    
                    // altrimenti carica metodo con parametro/i
                    call_user_func_array(array($this->controller, $action), $params);
                
                }
                    
			}else{
				throw new Exception("Il metodo <strong>$action</strong> non esiste!");
			}
		
		}
        
        /**
		 * Carica il middleware
		 *
		 * @param string $name
		 * @param string $action
		 * @return object
		 * @throws exception
		 */
        public function getClassMiddleware($name, $action){
            
            // classe
            $class = 'Middleware\\'.$name;
            
            // carica classe se esiste
            if(class_exists($class)){
            
                $middleware = new $class;
                $middleware->{$action}();
            
            }else{
                throw new Exception("Il middleware <strong>$name</strong> non esiste!");
            }
            
        }
		
		/**
		 * Se il route non esiste 
		 * genera un errore 
		 *
		 * @param boolean $this->found
		 * @thorws exception 
		 */
		public function error(){
			if(!$this->found) $this->error404('Route non trovato!');
		}
        
        /**
         * Genera errore 404
         *
         * @param string|null $msg 
         */
        public function error404($msg = null){
			
            $controller = new Controller;
			$controller->loadError404($msg);
		
		}
		
		/**
		 * Ottiene il pattern del filtro
		 * utilizzato nel route
		 *
		 * @param string $route
		 * @return string
		 */
		public function getPattern($route){
			return '#^'.str_replace(array_keys($this->filters), $this->filters, $route).'$#';
		}
        
        /**
         * Ottiene controller e action specifici
         *
         * @param string $string 
         * @param string $action 
         * @param array|null $params
         */
        public static function redirect($name, $action, $params = null){
            
            $router = new Router;
            $router->getController($name);
            $router->getAction($action, $params);
            exit;
            
        }
        
        /**
         * Ottiene richiesta HTTP
         *
         * @return string
         */
        public static function getRequest(){
            
            $router = new Router;
            return $router->request();
            
        }
        
        /**
         * Verifica richiesta HTTP 
         *
         * @param string $value 
         * @return boolean
         */
        public static function requestIs($value){
            
            $router = new Router;
            return ($router->request() === $value) ? true : false;
            
        }
		
	}
