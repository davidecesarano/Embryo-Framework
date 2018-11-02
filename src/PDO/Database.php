<?php 

    namespace Embryo\Database;

    use Embryo\Database\Connection;
    use Embryo\Database\Query;
    use Embryo\Database\QueryBuilder;

    class Database
    {
        private $connections;
        private $pdo;

        /**
         * Sets primary connection.
         *
         * @param [type] $connections
         * @return self
         */
        public function __construct($connections)
        {
            $this->connections = $connections;
            $this->pdo = $this->connections->getPdo('local');
        }

        /**
         * Sets a new connection.
         *
         * @param [type] $name
         * @return void
         */
        public function connection($name = null)
        {
            $this->pdo = $this->connections->getPdo($name);
            return $this;
        }

        public function table($name)
        {
            return new QueryBuilder($this->pdo, $name);
        }

        public function select($query, array $values = [])
        {
            return (new Query($this->pdo))
                ->query($query)
                ->values($values)
                ->executeAndFetch();
        }

        public function insert($query, array $values)
        {
            return (new Query($this->pdo))
                ->query($query)
                ->values($values)
                ->executeAndReturnId();
        }

        public function update($query, array $values)
        {
            return (new Query($this->pdo))
                ->query($query)
                ->values($values)
                ->execute();
        }

        public function delete($query, array $values)
        {
            return (new Query($this->pdo))
                ->query($query)
                ->values($values)
                ->execute();
        }
    }