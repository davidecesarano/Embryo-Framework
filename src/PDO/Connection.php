<?php 

    namespace Embryo\Database;

    use \PDO;

    class Connection
    {
        private $instances = [];

        public function __construct(array $databases)
        {
            foreach ($databases as $name => $database) {
                $this->setPdo($database, $name);
            }
        }

        public function getPdo($name)
        {
            return $this->instances[$name];
        }

        private function setPdo(array $database, $name)
        {
            $dsn      = $database['engine'].':dbname='.$database['name'].";host=".$database['host'].";charset=utf8mb4";
            $options  = [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION
            ];
            
            try {
                return $this->instances[$name] = new PDO($dsn, $database['user'], $database['password'], $options);
            } catch (PDOException $e) {
                // ...
            }
        }
    }