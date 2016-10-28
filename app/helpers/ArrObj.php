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
         * Aggiunge campo all'array 
         *
         * @param array $array 
         * @return array 
         */
        public function push($array){
            
            foreach($this->array as $key => $value){
                
                /*if(is_array($value)){
                    
                    foreach($array as $k => $v){
                        $this->array[$key][$k] = $v;
                    }
                    
                }else{*/
                    
                    foreach($array as $k => $v){
                        $this->array[$k] = $v;
                    }
                    
                //}
            
            }
  
        }
        
        /**
         * Aggiunge campo all'array da un oggetto (classe) 
         * esterno il cui parametro della funzione è 
         * contenuto nell'array
         *
         * @param mixed $name 
         * @param object $class
         * @param string $method 
         * @param mixed $obj
         * @return array
         */
        public function pushObjectInArray($name, $class, $method, $obj){
            
            foreach($this->array as $key => $value){
                if(is_array($value)){
                    $this->array[$key][$name] = $class->{$method}($value[$obj]);  
                }else{
                    $this->array[$name] = $class->{$method}($value[$obj]);
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
        
        public function pagination($page){
            
            $total = count($this->array); //total items in array    
            $limit  = 4; //per page    
            $totalPages  = ceil($total/$limit); //calculate total pages
            $page = max($page, 1); //get 1 page when $_GET['page'] <= 0
            $page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
            $offset = ($page - 1) * $limit;
            if($offset < 0 ) $offset = 0;
            return array_slice($this->array, $offset, $limit);
            
        }
        
        public function slice($start, $end){
            return array_slice($this->array, $start, $end);
        }
        
        /**
         * Verifica se l'array è multidimensionale
         *
         * @return boolean
         */
        public function is_multi(){
            
            rsort($this->array);
            return isset($this->array[0]) && is_array($this->array[0]);
            
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
            return json_decode(json_encode($this->array));
        }
        
        //
        public function getSingle(){
            
            if(count($this->array) == 1){
               
                $key = array_keys($this->array);
                return $this->array[$key[0]];
            
            }else{
                return $this->array;
            }
        
        }
        
    }