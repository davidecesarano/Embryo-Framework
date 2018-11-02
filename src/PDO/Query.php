<?php 

    namespace Embryo\Database;

    class Query 
    {
        private $pdo;
        private $query;
        private $stmt;

        public function __construct($pdo)
        {
            $this->pdo = $pdo;
        }

        public function query($query)
        {
            $this->query = $query;
            $this->stmt = $this->pdo->prepare($this->query);
            return $this;
        }

        public function values(array $values)
        {
            foreach ($values as $key => $value) {
                
                if (!is_array($value)) {
                    $this->stmt->bindValue(":$key", $value);
                } else {
                    
                    foreach($value as $k => $v){
                        $this->stmt->bindValue(":$k", $v);
                    }
                    
                }
                
            }
            return $this;
        }

        public function execute()
        {
            $this->stmt->execute();
        }

        public function executeAndReturnId()
        {
            $this->execute();
            return $this->pdo->lastInsertId();
        }

        public function executeAndFetch()
        {
            $this->execute();
            return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
        }
    }