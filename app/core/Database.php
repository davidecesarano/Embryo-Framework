<?php namespace Core;
	
	/**
	 * Database
	 *
	 * @author Davide Cesarano
	 */
	
	use PDO;
    use PDOException;
	use Core\Error;
	
	class Database extends PDO{
		
        /**
		 * Connessione al database MySQL
		 */
        public function __construct($database = array()){
            
            if(is_array($database)){
                
                // database
                $engine   = $database['engine']; 
                $host     = $database['host'];
                $name     = $database['name'];
                $user     = $database['user']; 
                $password = $database['password'];
                
                try{

                    // connessione
                    $dsn = $engine.':dbname='.$name.";host=".$host; 
                    parent::__construct($dsn, $user, $password);
                    $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                }catch(PDOException $e){
                    
                    // errore
                    Error::exceptionHandler($e);
                    
                }
                
            }else{
                throw new Exception("Formato database non valido!");
            }

        }
		
		/**
		 * Esegue la query
		 *
		 * @example $this->query('SELECT * FROM table')
		 * @param string $sql SQL query
		 * @return this
		 */
		public function query($query){
			
			$this->sql = $query;
			$this->sth = $this->prepare($this->sql);
			return $this;
			
		}
		
		/**
		 * Esegue il bindValue
		 *
		 * @example $this->value(array('key' => 'value'))
		 * @param array $values bindValue
		 * @return this
		 */
		public function values($values){
			
			foreach($values as $key => $value){
				
				if(!is_array($value)){
					
					// se il parametro non è un array esegue
					// il bindValue
					$this->sth->bindValue(":$key", $value);
				
				}else{
					
					// se il parametro è un array (utile per WHERE IN())
					// cicla il bindValue con chiave diversa
					foreach($value as $k => $v){
						$this->sth->bindValue("$k", $v);
					}
					
				}
				
			}
			return $this;
			
		}
		
		/**
		 * Esegue la query
		 *
		 * @return boolean
		 */
		public function execute(){
			return ($this->sth->execute()) ? true : false;
		}
        
        public function get(){
            
            try{
                
                // esegui
                $execute = $this->sth->execute();
                
                if($execute){
                
                    if($this->sth->rowCount() > 1){
                        
                        // crea array di oggetti
                        $obj = array();
                        while($result = $this->sth->fetch(PDO::FETCH_OBJ)){
                            $obj[] = $result;
                        }
                        return $obj;
                        
                    }else{
                        
                        // crea oggetti
                        return $this->sth->fetch(PDO::FETCH_OBJ);
                        
                    }
                    
                }else{
                    return false;
                }
                
            }catch(PDOException $e){
				
				// errori
				Error::database($e);
				
			}
            
        }
		
		/**
		 * Estrae una singola riga
		 *
		 * @return object/boolean
		 */
		public function getSingle(){
			
            try{
                
                // esegui
                $execute = $this->sth->execute();
                
                // crea oggetti
                return ($execute) ? $this->sth->fetch(PDO::FETCH_OBJ) : false;
                
            }catch(PDOException $e){
				
				// errori
				Error::database($e);
				
			}
			
		}
		
		/**
		 * Genera un array di oggetti
		 *
		 * @return array/object|boolean
		 */
		public function getArray(){
			
			try{
				
				// esegui
				$execute = $this->sth->execute();
				
                if($execute){
                
                    // crea array di oggetti
                    $obj = array();
                    while($result = $this->sth->fetch(PDO::FETCH_OBJ)){
                        $obj[] = $result;
                    }
                    return $obj;
                    
                }else{
                    return false;
                }
				
			}catch(PDOException $e){
				
				// errori
				Error::database($e);
				
			}
			
		}
		
		/**
		 * Genera un array associativo/numerico
		 *
		 * @return array|boolean
		 */
		public function getFetchAll(){
			
			try{
				
				// esegui
				$execute = $this->sth->execute();
				
                // crea array
                return ($execute) ? $this->sth->fetchAll() : false;
			
			}catch(PDOException $e){
				
				// errori
				Error::database($e);
				
			}
			
		}
        
        /**
		 * Genera un array associativo/numerico
		 *
		 * @return array|boolean
		 */
		public function getFetchAssoc(){
			
			try{
				
				// esegui
				$execute = $this->sth->execute();
				
                // crea array
                return ($execute) ? $this->sth->fetch(PDO::FETCH_ASSOC) : false;
			
			}catch(PDOException $e){
				
				// errori
				Error::database($e);
				
			}
			
		}
		
		/**
		 * Restituisce il numero delle righe
		 *
		 * @return int
		 */
		public function getRowCount(){
		
			// esegui
			$execute = $this->sth->execute();
			
			// restituisce numero
			return ($execute) ? $this->sth->rowCount() : 0;
			
		}
		
		/**
		 * Inserisce righe
		 *
		 * @example $this->insert('table_name', array('name' => $name))
		 * @param string $table
		 * @param array $data
		 * @return int|boolean this|false - ultimo id inserito|false
		 */
		public function insert($table, $data){
			
			// imposta nomi e valori dall'array $data
			$names = implode(", ", array_keys($data));
			$values = ':'.implode(", :", array_keys($data));
			
			// query
			$sth = $this->prepare("INSERT INTO ".$table." (".$names.") VALUES (".$values.")");
			
			// valori
			foreach($data as $key => $value){
				$sth->bindValue(":$key", $value);
			}
			
			// esegui
			$execute = $sth->execute();
			
			// esito
			if($execute) return $this->lastInsertId();
		
		}
		
		/**
		 * Aggiorna campi
		 *
		 * @example $this->update('table_name', array('name' => $name), array('id' => $id))
		 * @param string $table
		 * @param array $data
		 * @param string $where_data
		 * @return boolean
		 */
		public function update($table, $data, $where_data){

			// campi
			ksort($data);
			$fields = NULL;
			foreach($data as $key => $value){
				$fields .= "`$key`=:$key,";
			}
			$fields = rtrim($fields, ',');
			
			// where
			$where = '';
			foreach($where_data as $key => $value){
				$where .= "`$key`=:$key,";
			}
			$where = rtrim($where, ',');
			
			// query
			$sth = $this->prepare("UPDATE $table SET $fields WHERE $where");
			
			// valori
			foreach($data as $key => $value){
				$sth->bindValue(":$key", $value);
			}
			
			// esegui
			$execute = $sth->execute();
			
			// esito
			if($execute) return true;
		
		}
		
		/**
		 * Elimina righe 
		 *
		 * @param string $table
		 * @param array $data
		 * @return boolean
		 */
		public function delete($table, $data){
			
			// where
			$where = '';
			foreach($data as $key => $value){
				$where .= "`$key`=:$key,";
			}
			$where = rtrim($where, ',');
			
			// query
			$sth = $this->prepare("DELETE FROM $table WHERE $where");
			
			// valori
			foreach($data as $key => $value){
				$sth->bindValue(":$key", $value);
			}
			
			// esegui
			$execute = $sth->execute();
			
			// esito
			if($execute) return true;
			
		}
		
		/**
		 * Crea Tabella
		 *
		 * @param string $sql
		 * @return boolean
		 */
		public function createTable($sql){
			
			// esegui
			$execute = $this->exec($sql);
			
			// esito
			if($execute) return true;
			
		}
		
		/**
		 * Utility per il debug 
		 *
		 * @return string this
		 */
		public function debug(){
			return $this->sql;			
		}
		
	}
