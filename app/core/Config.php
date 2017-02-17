<?php namespace Core;
    
    /**
     * Config
     *
     * Setta e invoca le configurazioni dell'applicazione.
     *
     * @author Davide Cesarano
     */
    
    use \Exception;
    use Helpers\Database;
    
    class Config {
        
        /**
         * @var array $files 
         */
        public static $files = array();
        
        /**
         * @var array $database 
         */
        public static $database = array();
        
        /**
         * @var array $setting 
         */
        public static $setting = array();
        
        /**
         * @var string $model
         */
        protected static $model = '\Models\Dashboard\Setting';
        
        /**
         * Setta un'impostazione
         *
         * @param string $name 
         * @param array $values
         * @return array
         */
        public static function set(){
            
            // recupera configurazioni dai file
            foreach(glob(FOLDER_CONFIG.'/*.php') as $file) {
                static::$files += include $file;
            }
            
            if(static::$files['app']['driver'] == 'files'){
                
                // configurazioni file
                static::$setting = static::$files;
            
            }elseif(static::$files['app']['driver'] == 'database'){
                
                if(class_exists(static::$model)){
                    
                    // configurazioni database
                    $model = new static::$model;
                    static::$database = $model->listAllOptions();
                    static::$setting = array_replace_recursive(static::$files, static::$database);
                    
                }else{
                    throw new Exception("La classe <strong>".static::$model."</strong> non esiste!");
                }
                
            }else{
                throw new Exception("Il driver delle configurazioni non &egrave; impostato correttamente!");
            }
            
        }
        
        /**
         * Restituisce un'impostazione generale
         * o un'impostazione specifica
         *
         * @example config::get('app', 'meta.title')
         * @example config::get('app', 'meta')
         * @example config::get('app')
         * @param string $name 
         * @param mixed|null $value
         * @return mixed
         * @throws exception
         */
        public static function get($name, $value = null){

            if(isset(static::$setting[$name])){
                
                if($value){
                    
                    if(strpos($value, '.') !== false){
                        
                        $segment = explode('.', $value);
                        $array = $segment[0];
                        $key = $segment[1];
                        
                        if(isset(static::$setting[$name][$array])){
                        
                            if(array_key_exists($key, static::$setting[$name][$array])){
                                return static::$setting[$name][$array][$key];
                            }else{
                                throw new Exception("L'impostazione <strong>$key</strong> dell'impostazione di <strong>$array</strong> non esiste!");
                            }
                            
                        }else{
                            throw new Exception("L'impostazione <strong>$array</strong> per la configurazione <strong>$name</strong> non esiste!");
                        }
                        
                    }else{
                        
                        if(array_key_exists($value, static::$setting[$name])){
                            return static::$setting[$name][$value];
                        }else{
                            throw new Exception("L'impostazione <strong>$value</strong> per la configurazione <strong>$name</strong> non esiste!");
                        }
                        
                        
                    }
                    
                }else{
                    return static::$setting[$name];
                }
                
            }else{
                throw new Exception("La configurazione <strong>$name</strong> non esiste!");
            }
            
        }
        
    }