<?php namespace Core;
    
    /**
	 * Config
	 *
	 * Setta e invoca le configurazioni dell'applicazione.
	 *
	 * @author Davide Cesarano
	 */
    
    use \Exception;
    
    class Config {
        
        /**
         * @var array $settings 
         */
        protected static $settings = array();
        
        /**
         * Setta un'impostazione
         *
         * @param string $name 
         * @param array $values
         * @return array
         */
        public static function set($name, $values){
            
            static::$settings[$name] = array();
            foreach($values as $key => $value){
                
                if(!is_array($value)){
                    static::$settings[$name][$key] = $value;
                }else{
                    
                    foreach($value as $k => $v){
                        static::$settings[$name][$key][$k] = $v;
                    }
                    
                }
                
                
            }
            
        }
        
        /**
         * Restituisce un'impostazione generale
         * o un'impostazione specifica
         *
         * @example config::get('app', 'meta.title')
         * @example config::get('app', 'meta')
         * @param string $name 
         * @param mixed $value
         * @return mixed
         * @throws exception
         */
        public static function get($name, $value){
            
            if(isset(static::$settings[$name])){
            
                if(strpos($value, '.') !== false){
                    
                    $segment = explode('.', $value);
                    $array = $segment[0];
                    $key = $segment[1];
                    
                    if(isset(static::$settings[$name][$array])){
                    
                        if(array_key_exists($key, static::$settings[$name][$array])){
                            return static::$settings[$name][$array][$key];
                        }else{
                            throw new Exception("L'impostazione <strong>$key</strong> dell'impostazione di <strong>$array</strong> non esiste!");
                        }
                        
                    }else{
                        throw new Exception("L'impostazione <strong>$array</strong> per la configurazione <strong>$name</strong> non esiste!");
                    }
                    
                }else{
                    
                    if(array_key_exists($value, static::$settings[$name])){
                        return static::$settings[$name][$value];
                    }else{
                        throw new Exception("L'impostazione <strong>$value</strong> per la configurazione <strong>$name</strong> non esiste!");
                    }
                    
                    
                }
                
            }else{
                throw new Exception("La configurazione <strong>$name</strong> non esiste!");
            }
        
        }
        
        /**
         * Restitutisce tutte le impostazioni 
         * per una specifica configurazione
         *
         * @param string $name 
         * @return array
         */
        public static function all($name){
            return static::$settings[$name];
        }
        
    }