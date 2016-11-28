<?php namespace Helpers;

	/**
	 * Token
     *
	 * @author Davide Cesarano
	 */
	
	use Core\Config;
	use Helpers\Session;
	
	class Token {
        
		/**
		 * Genera token
		 *
		 * @return array
		 */
		public static function set($name){

            if(!Session::get('token_'.$name)){
                
                $origin = $_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'];
                $salt = random_string();
                $hash = base64_encode(time() . hash_crypt($origin, $salt));
                
                Session::set('token_'.$name.'_salt', $salt);
                Session::set('token_'.$name, $hash);
            
            }
            return Session::get('token_'.$name);
		
        }
        
        public static function get($name){
            return (Session::get('token_'.$name)) ? Session::get('token_'.$name) : false;
        }
		
		/**
		 * Verifica se il token è valido
		 *
		 * @param string $name
		 * @param string $token
		 * @param int|null $timelife
		 * @return boolean 
		 */
		public static function is_valid($name, $token, $timelife = null){

            if(Session::get('token_'.$name) && Session::get('token_'.$name.'_salt') && $token != ''){
                
                $origin = $_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'];
                $salt = Session::get('token_'.$name.'_salt');
                $hash = Session::get('token_'.$name);
                
                $token_decoded = base64_decode($token);
                $token_time = substr($token_decoded, 0, 10);
                $token_hash = substr($token_decoded, 10);
                
                if($timelife){
                    
                    $limit = time() - ($timelife * 24);
                    return ($token_time > $limit) ? true : false;
                    
                }
                
                return ($token_hash === hash_crypt($origin, $salt)) ? true : false;
                
            }else{
                return false;
            }
            
		}        
		
	}