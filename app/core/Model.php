<?php namespace Core;
    
    /**
     * Model
     *
     * Gestisce i dati, risponde alle richieste sullo stato ed esegue 
     * le istruzioni di modifica dello stato stesso.
     *
     * @author Davide Cesarano
     */
    
    use Helpers\Database;
    
    class Model {
        
        /**
         * @var object $db 
         */
        protected $db;
        
        /**
         * Connessione al database MySQL
         *
         * @param string|null $database
         * @return object $this->db
         */		
        public function database($database = null){
            $this->db = Database::connection($database);
        }
        
        /**
         * Carica il modello
         *
         * @param string $name Nome del modello
         * @return object $this->name_model
         * @throws exception
         */
        public function loadModel($name){
            
            // nome e classe
            if(strstr($name, '\\')){ 
                
                $exp = explode('\\', $name);
                $class = 'Models\\';
                foreach($exp as $key => $value){
                    $class .= ucfirst($value).'\\';
                }
                $class = rtrim($class, '\\');
                $name_model = end($exp).'_model';
            
            }else{
                
                $class = 'Models\\'.ucfirst($name);
                $name_model = $name.'_model';
           
            }
            
            // carica modello se esiste
            if(class_exists($class)){
                $this->{$name_model} = new $class;
            }else{
                throw new Exception("Il modello $name non esiste!");
            }
            
        }
        
    }