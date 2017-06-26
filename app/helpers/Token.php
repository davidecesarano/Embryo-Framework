<?php namespace Helpers;

    /**
     * Token
     *
     * @author Davide Cesarano
     */
    
    use Helpers\Session;
    
    class Token {
        
        /**
         * Genera token
         *
         * @param string $name
         * @return array
         */
        public static function set($name){

            if(!Session::get('token_'.$name)){
                
                $origin = self::origin();
                $salt = random_string();
                $hash = base64_encode(time() . hash_crypt($origin, $salt));
                
                Session::set('token_'.$name.'_salt', $salt);
                Session::set('token_'.$name, $hash);
            
            }
            return Session::get('token_'.$name);
        
        }
        
        /**
         * Ottiene contenuto token 
         *
         * @param string $name
         * @return string|boolean 
         */
        public static function get($name){
            return (Session::get('token_'.$name)) ? Session::get('token_'.$name) : false;
        }
        
        /**
         * Ottiene un valore per la composizione del token 
         *
         * @return string 
         */
        public static function origin(){
            return $_SERVER['REMOTE_ADDR'].user_agent();
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
                
                $origin = self::origin();
                $salt_session = Session::get('token_'.$name.'_salt');
                $token_session = Session::get('token_'.$name);
                
                $token_decoded = base64_decode($token_session);
                $token_time = substr($token_decoded, 0, 10);
                $token_hash = substr($token_decoded, 10);
                
                if($token_session == $token){
                
                    if($timelife !== null){
                        
                        $limit = time() - ($timelife * 24);
                        return ($token_time > $limit) ? true : false;
                        
                    }
                    
                    return ($token_hash == hash_crypt($origin, $salt_session)) ? true : false;
                
                }else{
                    return false;
                }
                
            }else{
                return false;
            }
            
        }        
        
    }