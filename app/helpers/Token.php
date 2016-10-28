<?php namespace Helpers;

	/**
	 * Token
     *
	 * @author Davide Cesarano
	 */
	
	use Helpers\Session;
    use \Exception;
	
	class Token{
        
		/**
		 * Genera token con relativa scadenza (30 minuti)
         * salvandoli in una sessione
		 *
		 * @return array
		 */
		public static function set(){
            
            $max_time = 30 * 60;
            $token = self::get();
            $token_time = self::get('token_time');
            
            if((($max_time + $token_time) <= time()) || empty($token)){
                
                Session::set('token', md5(uniqid(mt_rand(), true)));
                Session::set('token_time', time());
                
            }
		
        }
		
		/**
		 * Ottiene il token creato e
		 * salvato in una sessione
		 *
		 * @param string $name
		 * @return string 
		 */
		public static function get($name = null){
			return ($name) ? Session::get($name) : Session::get('token');
        }
		
		/**
		 * Verifica se il token è valido
		 *
		 * @param string $name
		 * @param string $token
		 * @return boolean 
		 */
		public static function is_valid($token){
			return ($token === self::get());
		}        
		
	}