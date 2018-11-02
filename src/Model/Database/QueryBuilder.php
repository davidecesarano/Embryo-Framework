<?php 

    namespace Embryo\Model\Database;

    class QueryBuilder
    {
        public function __construct($pdo)
        {
            $this->pdo = $pdo;
        }

        public function query($query)
        {
            $this->sth = $this->pdo->prepare($query);
            return $this;
        }

        public function all()
        {
            $this->sth->execute();
            return $this->sth->fetchAll(\PDO::FETCH_OBJ);
        }

        public function select()
        {

        }

        public function update()
        {
            
        }

        public function delete()
        {
            
        }
    }