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
         * @var \Embryo\PDO\Database $pdo
         */
        protected $pdo;

        /**
         * Set pdo connection.
         */
        public function __construct()
        {
            $this->pdo = Container::get('database')->connection($this->connection);
        }

        /**
         * Set connection.
         *
         * @param string $connection
         * @return \Embryo\PDO\Connection
         */
        final protected function connection(string $connection)
        {
           return Container::get('database')->connection($connection);
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