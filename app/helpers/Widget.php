<?php namespace Helpers;
    
    /**
     * Widget 
     *
     * Si occupa della gestione dei widgets
     * da utilizzare nel template.
     *
     * @author Davide Cesarano
     */
     
    use Core\Config;
    
    class Widget {
        
        /**
         * Invoca funzione nei controllers dei
         * widgets 
         *
         * @param array self::$widgets
         * @param string $name
         * @param array $params
         */
        public static function __callStatic($name, $params){
            
            $widgets = Config::get('widgets');
            
            foreach($widgets as $widget){
                
                if(class_exists($widget)){
                    
                    if(method_exists($widget, $name)){
                        return call_user_func_array(array(new $widget, $name), $params);  
                    }
                    
                }
                
            }
  
        }
        
    }