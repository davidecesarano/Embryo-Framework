<?php namespace Helpers;
	
	/**
	 * Session
	 *
	 * Gestisce le informazioni contenute nelle sessioni.
	 *
	 * @author Davide Cesarano
	 */
	
	class Session{
		
		/**
		 * Avvia la sessione
		 */
		public static function init(){
			
			/*  
			* Il simbolo @ prima del nome della 
			* variabile/funzione equivale a:
			* if(isset($var)){
			* 	$var_2 = $var;
			* }else{
			* 	$var_2 = null;
			* }
			*/  
			@session_start();
		
		}
		
		/**
		 * Crea chiave/valore nell'array $_SESSION
		 *
		 * @param string $key (es. = 'loggedIn')
		 * @param boolean $value (es. = true)
		 * @return array $_SESSION[$key]
		 */
		public static function set($key, $value){
			$_SESSION[$key] = $value;
		}
		
		/**
		 * Ottiene chiave/valore dell'array $_SESSION
		 *
		 * @param string $key (es. = 'loggedIn')
		 * @return array $_SESSION[$key]
		 */
		public static function get($key){
			if(isset($_SESSION[$key])) return $_SESSION[$key];
		}
		
		/**
		 * Controlla se la sessione è attiva,
		 * altrimenti distrugge la sessione
		 *
		 * @param string $name Nome della sessione
		 * @param string $redirect
		 * @return void
		 */
		public static function check($name, $redirect){
			
			if(self::get($name) == false){
				self::destroy($redirect);
			}
			
		}
		
		/**
		 * Chiude la sessione ed esegue il redirect
		 * alla pagina login
		 * 
		 * @param string $redirect
		 * @return void
		 */
		public static function destroy($redirect){
			
			// distrugge
			$_SESSION = array();
			session_destroy();
			
			// redirect
			redirect($redirect);
		
		}
	
	}