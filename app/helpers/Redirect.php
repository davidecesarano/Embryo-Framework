<?php namespace Helpers;
	
	/**
	 * Redirect 
	 *
	 * Collezione di funzioni utili per gli urls
	 *
	 * @author Davide Cesarano
	 */
	
    use Core\Router;
    
	class Redirect {
		
		/**
		 * Redirect a pagine interne 
         * dell'applicazione
		 *
		 * @param string $url
		 */
		public static function get($url){
			header('Location: '.SITE_URL.'/'.$url);
			exit;
		}
        
        /**
		 * Redirect a pagine esterne
         * all'applicazione
		 *
		 * @param string $url
		 */
		public static function out($url){
			header('Location: '.$url);
			exit;
		}
        
        public static function route($route){
            
            $router = new Router;
            $router->getController();
            
        }
		
	}