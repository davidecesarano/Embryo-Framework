<?php namespace Helpers;
    
    /**
     * ArrObj
     *
     * Si occupa della gestione degli array e
     * di array di oggetti.
     *
     * @author Davide Cesarano
     */
    
    class ArrObj {
        
        /**
         * @var array $vars
         */
        public $array = array();

        /**
         * Crea array
         *
         * @param array $array 
         * @return array
         */
        public function __construct($array){
            
            if(is_array($array) || is_object($array)){

                foreach($array as $key => $value){
                    $this->array[$key] = (is_array($value) || is_object($value)) ? (array) $value : $value;
                }
            
            }
            
        }        
        
        /**
         * Aggiunge elemento all'array 
         *
         * @param string $value
         * @return array 
         */
        public function push($value){
            array_push($this->array, $value);
        }
        
        /**
         * Aggiunge array all'array 
         *
         * @param array $array 
         * @return array 
         */
        public function pushArray($array){
            
            if(!$this->is_multi()){
                $this->array = array_merge_recursive($this->array, $array);
            }else{
                
                foreach($this->array as $key => $value){
                    foreach($array as $k => $v){
                        $this->array[$key][$k] = $v;
                    }
                }
                
            }
            
        }
        
        /**
         * Aggiunge elemento all'array da un oggetto 
         * esterno il cui parametro della funzione è 
         * contenuto nell'array
         *
         * @param mixed $name 
         * @param object $class
         * @param string $method 
         * @param mixed $obj
         * @return array
         */
        public function pushInnerObject($name, $class, $method, $param){
            
            foreach($this->array as $key => $value){
                
                if(is_array($value)){
                    $this->array[$key][$name] = $class->{$method}($value[$param]);  
                }else{
                    $this->array[$name] = $class->{$method}($value[$param]);
                }
                
            }
            
        }
        
        /**
         * Aggiunge elemento all'array da un oggetto 
         * esterno
         *
         * @param mixed $name 
         * @param object $class
         * @param string $method 
         * @param mixed $obj
         * @return array
         */
        public function pushOuterObject($name, $class, $method, $param = null){
            
            foreach($this->array as $key => $value){
                
                if(is_array($value)){
                    $this->array[$key][$name] = $class->{$method}($param);  
                }else{
                    $this->array[$name] = $class->{$method}($param);
                }
                
            }
            
        }
        
        /**
         * Esegue un filtro semplice sull'array 
         *
         * @param mixed $key Chiave del campo
         * @param string $operator Operatore di confronto
         * @param mixed $valure Valore del campo
         * @return array 
         */
        public function filter($key, $operator, $value){
            
            $this->array = array_filter($this->array, function($array) use($key, $operator, $value){
                
                switch($operator){
                    case '===':
                        return $array[$key] === $value;
                        break;
                    case '==':
                        return $array[$key] == $value;
                        break;
                    case '>':
                        return $array[$key] > $value;
                        break;
                    case '>=':
                        return $array[$key] >= $value;
                        break;
                    case '<':
                        return $array[$key] < $value;
                        break;
                    case '<=':
                        return $array[$key] <= $value;
                        break;
                    case '!=':
                        return $array[$key] != $value;
                        break;
                }
                
            });
        
        }
        
        /**
         * Cerca elemento simile (LIKE %value%) nel valore di un array
         *
         * @param mixed $key 
         * @param mixed $value
         * @return array 
         */
        public function search($key, $value){
            
            $arr = array();
            
            if($this->is_multi()){
                
                foreach ($this->array as $k => $v) {
                    if(stripos($v[$key], $value) !== FALSE) {
                        array_push($arr, $v);
                    }
                }
            
            }
            return $arr;
        
        }
        
        /**
         * Verifica se l'array è multidimensionale
         *
         * @return boolean
         */
        public function is_multi(){
            
            if(count($this->array) == count($this->array, COUNT_RECURSIVE)){
                return false;
            }else{
                return true;
            }
            
        }
        
        /**
         * Restituisce array singolo da array
         * multidimensionale con una chiave 
         *
         * @return array 
         */
        public function getSingle(){
            
            if(count($this->array) == 1){
               
                $key = array_keys($this->array);
                return $this->array[$key[0]];
            
            }else{
                return $this->array;
            }
        
        }
        
        /**
         * Restituisce un array 
         *
         * @param boolean $multi
         * @return array 
         */
        public function getArray(){
            return $this->array;
        }
        
        /**
         * Restituisce oggetti o un array di oggetti
         *
         * @return object/array 
         */
        public function getObjects(){
            
            if($this->is_multi()){
                return json_decode(json_encode($this->array));
            }else{
                return (object) $this->array;
            }
            
        }
        
    }