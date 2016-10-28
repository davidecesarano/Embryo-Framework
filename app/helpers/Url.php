<?php namespace Helpers;
	
	/**
	 * Url 
	 *
	 * Collezione di funzioni utili per gli urls
	 *
	 * @author Davide Cesarano
	 */
	
	class Url{
		
		/**
		 * Redirect
		 *
		 * @param string $url
		 * @return void
		 */
		public static function redirect($url){
			header('Location: '.$url);
			exit;
		}
		
	}