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
		 * @param array $database
		 * @return object $this->db
		 */		
		public function database($database){
			$this->db = Database::get($database);
		}
		
		/**
		 * Verifica se la connessione è riuscita o fallita
		 *
		 * @return boolean
		 */
		public function connection(){
			return (is_null($this->db)) ? false : true;
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
                $class = 'Models\\'.ucfirst($exp[0]).'\\'.ucfirst($exp[1]);
                $name_model = $exp[1].'_model';
            
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