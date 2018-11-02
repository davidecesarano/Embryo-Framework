<?php

    /**
     * Connection
     *
     *
     *
     *
     */
    
    namespace Embryo\Model\Database;

    use \PDO;

    class Connection
    {
        private $databases = [];

        public function withDatabase(array $databases)
        {
            $this->databases = $databases;
            return $this;
        }

        public function pdo($name = 'local')
        {
            $database = $this->databases[$name];
            $engine   = $database['engine'];
            $host     = $database['host'];
            $name     = $database['name'];
            $user     = $database['user'];
            $password = $database['password'];
            $dsn      = $engine.':dbname='.$name.";host=".$host.";charset=utf8mb4";
            $options  = [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION
            ];
            
            try {
                return new PDO($dsn, $user, $password, $options);
            } catch (PDOException $e) {
                // ...
            }
        }
    }
