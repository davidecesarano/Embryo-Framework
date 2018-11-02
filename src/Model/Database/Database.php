<?php 

    namespace Embryo\Model\Database;

    use Embryo\Model\Database\Connection;
    use Embryo\Model\Database\QueryBuilder;

    class Database 
    {
        private $container;
        private $queryBuilder;

        public function __construct($container)
        {
            $this->container = $container;
        }

        public function connection($name = 'local')
        {
            $this->pdo = $this->container['connection']->pdo($name);
            $this->queryBuilder = new QueryBuilder($this->pdo);
            return $this;
        }

        public function query($query, $data = [])
        {
            $this->connection();
            return $this->queryBuilder->query($query)->all();
        }

        public static function table($name)
        {

        }
    }