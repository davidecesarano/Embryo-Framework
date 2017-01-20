<?php namespace Helpers;
    
    /**
     * Session
     *
     * Gestisce le informazioni contenute nelle sessioni.
     *
     * @author Davide Cesarano
     */
    
    use Core\Session as SessionHandler;
    
    class Session{
        
        /**
         * Avvia la sessione
         */
        public static function init(){
            
            // gestore sessioni
            new SessionHandler;
            
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
         * @param mixed $key
         * @param mixed $value
         */
        public static function set($key, $value){
            $_SESSION[$key] = $value;
        }
        
        /**
         * Ottiene chiave/valore dell'array $_SESSION
         *
         * @param mixed $key
         * @return string
         */
        public static function get($key){
            if(isset($_SESSION[$key])) return $_SESSION[$key];
        }
        
        /**
         * Verifica se la chiave dell'array $_SESSION esiste
         *
         * @param mixed $key
         * @return boolean
         */
        public static function exists($key){
            return (isset($_SESSION[$key])) ? true : false;
        }
        
        /**
         * Chiude la sessione ed esegue il redirect
         * alla pagina login
         * 
         * @param string|null $redirect
         */
        public static function destroy($redirect = null){
            
            session_unset();
            session_destroy();
            
            // redirect
            if($redirect){
                redirect($redirect);
            }
        
        }
    
    }