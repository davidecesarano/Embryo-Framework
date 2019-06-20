<?php 

    /**
     * Model
     * 
     * Model abstract class for connecting database.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link https://github.com/davidecesarano/embryo-framework
     */

    namespace Embryo;

    use Embryo\Facades\Container;

    abstract class Model 
    {
        /**
         * @var string $connection
         */
        protected $connection = 'local';
        
        /**
         * @var Database $database
         */
        private $database;
        
        /**
         * @var Database $pdo
         */
        protected $pdo;

        /**
         * Set database and pdo connection.
         */
        public function __construct()
        {
            $this->database = Container::get('database');
            $this->pdo      = $this->database->connection($this->connection);
        }

        /**
         * Set connection.
         *
         * @param string $connection
         * @return Database
         */
        final protected function connection(string $connection = 'local')
        {
           return $this->database->connection($connection);
        }

        /**
         * Get service from Container.
         *
         * @param string $key
         * @return mixed
         */
        final protected function get(string $key)
        {
            return Container::get($key);
        }
    }