<?php namespace Helpers;
    
    /**
     * Database
     *
     * @author Davide Cesarano
     */
    
    use Exception;
    use PDO;
    use PDOException;
    use Core\Config;
    use Core\Error;
    
    class Database {
        
        /**
         * @var array $instance
         */
        private static $instance = [];
        
        /**
         * @var obj $dbh 
         */
        private $dbh;
        
        /**
         * @var obj $stmt 
         */
        private $stmt;
        
        /**
         * Connessione al database MySQL
         *
         * @param array $database
         * @return obj
         */
        public function __construct($config_name){
            
            $database = Config::$files['database'][$config_name];
            
            if(is_array($database)){
                
                // database
                $engine   = $database['engine']; 
                $host     = $database['host'];
                $name     = $database['name'];
                $user     = $database['user']; 
                $password = $database['password'];
                $dsn      = $engine.':dbname='.$name.";host=".$host.";charset=utf8mb4"; 
                $options  = array(
                    PDO::ATTR_PERSISTENT => true,
                    PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION
                );
                
                try{

                    // connessione
                    $this->dbh = new PDO($dsn, $user, $password, $options);
                    
                }catch(PDOException $e){
                    
                    // errore
                    Error::exceptionHandler($e);
                    
                }
                
            }else{
                throw new Exception("Formato database non valido!");
            }

        }
        
        /**
         * Crea e/o invoca una connessione 
         *
         * @param string|null $name
         */
        public static function connection($name = null){
            
            $name = ($name) ? $name : 'local';
            
            if(!isset(self::$instance[$name])){
                self::$instance[$name] = new Database($name);
            }
            return self::$instance[$name];
            
        }
        
        /**
         * Esegue la query
         *
         * @example $this->query('SELECT * FROM table')
         * @param string $sql
         * @return this
         */
        public function query($query){
            
            $this->stmt = $this->dbh->prepare($query);
            return $this;
            
        }
        
        /**
         * Esegue il bindValue su valore singolo 
         *
         * @example $this->value(array('key' => 'value'))
         * @param string $param
         * @param mixed $value
         * @return this
         */
        public function value($param, $value){
           
            $this->stmt->bindValue(":$param", $value);
            return $this; 
  
        }
        
        /**
         * Esegue il bindValue su valori multipli
         *
         * @example $this->values(array('key' => 'value'))
         * @param array $values bindValue
         * @return this
         */
        public function values($values = array()){
            
            foreach($values as $key => $value){
                
                if(!is_array($value)){
                    
                    // se il parametro non è un array esegue
                    // il bindValue
                    $this->value($key, $value);
                
                }else{
                    
                    // se il parametro è un array (utile per WHERE IN())
                    // cicla il bindValue con chiave diversa
                    foreach($value as $k => $v){
                        $this->value($k, $v);
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
            
            try{
            
                $execute = $this->stmt->execute();
                if(!$execute){
                    throw new PDOException(0);
                }else{
                    return true;
                }
                
                
            }catch(PDOException $e){
                return $e;
            }
                
        }
        
        /**
         * Restituisce una riga 
         *
         * @return obj 
         */
        public function single(){
            
            $this->execute();
            return $this->stmt->fetch(PDO::FETCH_OBJ);
            
        }
        
         /**
         * Restituisce più righe
         *
         * @return array|obj 
         */
        public function all(){
            
            $this->execute();
            return $this->stmt->fetchAll(PDO::FETCH_OBJ);
            
        }
        
        /**
         * Restituisce il numero delle righe
         *
         * @return int
         */
        public function rowCount(){
        
            $this->execute();
            return $this->stmt->rowCount();
            
        }
        
        /**
         * Restituisce l'ultimo id inserito 
         *
         * @return int 
         */
        public function lastInsertId(){
            return $this->dbh->lastInsertId();  
        }
        
        /**
         * Esegue il debug della query e dei parametri 
         *
         * @return string 
         */
        public function debug(){
            return $this->stmt->debugDumpParams();
        }
        
        /**
         * Inserisce righe
         *
         * @example $this->insert('table_name', array('name' => $name))
         * @param string $table
         * @param array $data
         * @return int|bool
         */
        public function insert($table, $data){
            
            // imposta nomi e valori dall'array $data
            $names = implode(", ", array_keys($data));
            $values = ':'.implode(", :", array_keys($data));
            
            // query
            $this->query("INSERT INTO $table ($names) VALUES ($values)");
            
            // valori
            $this->values($data);
            
            // esegui
            $this->execute();
            
            // ultimo id inserito
            return $this->lastInsertId();
        
        }
        
        /**
         * Aggiorna campi
         *
         * @example $this->update('table_name', array('name' => $name), array('id' => $id))
         * @param string $table
         * @param array $data
         * @param array $where_data
         * @return boolean
         */
        public function update($table, $data, $where_data){

            // campi
            ksort($data);
            $fields = NULL;
            foreach($data as $key => $value){
                $fields .= "$key = :$key,";
            }
            $fields = rtrim($fields, ',');
            
            // where
            $where = '';
            foreach($where_data as $key => $value){
                $where .= "$key = :$key,";
            }
            $where = rtrim($where, ',');
            
            // query
            $this->query("UPDATE $table SET $fields WHERE $where");
            
            // valori
            $this->values($data);
            
            // esegui
            return $this->execute();
        
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
                $where .= "$key = :$key,";
            }
            $where = rtrim($where, ',');
            
            // query
            $this->query("DELETE FROM $table WHERE $where");
            
            // valori
            $this->values($data);

            // esegui
            return $this->execute();
            
        }
        
        /**
         * Chiude connessione
         *
         * @return null 
         */
        public function close(){
            $this->dbh = null;
        }
        
    }
