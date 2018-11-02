<?php 
    
    /**
     * Collection
     */

    namespace Embryo\Utils;
    
    use ArrayIterator;
    use Embryo\Utils\CollectionInterface;
     
    class Collection implements CollectionInterface 
    {    
        /**
         * @var array
         */
        protected $data = [];
        
        /**
         * Crea una collection 
         *
         * @param array $parameters 
         */
        public function __construct(array $parameters = [])
        {
            $this->append($parameters);
        }

        /**
         * Aggiunge parametri con valori 
         *
         * @param array $parameters 
         */
        public function append(array $parameters = [])
        {
            foreach ($parameters as $key => $value) {
                $this->set($key, $value);
            }
        }

        /**
         * Imposta un parametro 
         *
         * @param string $key 
         * @param mixed $value 
         */
        public function set($key, $value)
        {
            return $this->data[$key] = $value;
        }

        /**
         * Ottiene valore di un parametro.
         * Se non esiste ritorna un valore di default 
         *
         * @param string $key 
         * @param mixed $default
         * @return mixed
         */
        public function get($key, $default = null)
        {
            return $this->has($key) ? $this->data[$key] : $default;
        }
        
        /**
         * Restituisce tutti i valori 
         *
         * @return array 
         */
        public function all()
        {
            return $this->data;
        }
        
        /**
         * Restitiusce le chiavi dei valori 
         *
         * @param array $parameters
         * @return array 
         */
        public function keys(array $parameters = [])
        {
            return array_keys($parameters);
        }
        
        /**
         * Verifica se un parametro esiste 
         *
         * @param string $key 
         * @return bool 
         */
        public function has($key)
        {
            return array_key_exists($key, $this->data); 
        }
        
        /**
         * Rimuove parametro 
         *
         * @param string $key 
         */
        public function remove($key)
        {
            unset($this->data[$key]);
        }

        /**
         * Rimuove tutti i parametri
         */
        public function clear()
        {
            $this->data = [];
        }
        
        /**
         * ------------------------------------------------------------
         * ArrayAccess	
         * ------------------------------------------------------------
         */
        
        /**
         * $this->has($key)
         *
         * @param  string $key
         * @return bool
         */
        public function offsetExists($key)
        {
            return $this->has($key);
        }

        /**
         * $this->get($key)
         *
         * @param string $key
         * @return mixed
         */
        public function offsetGet($key)
        {
            return $this->get($key);
        }

        /**
         * $this->set($key, $value)
         *
         * @param string $key
         * @param mixed  $value
         */
        public function offsetSet($key, $value)
        {
            $this->set($key, $value);
        }

        /**
         * $this->remove($key)
         *
         * @param string $key
         */
        public function offsetUnset($key)
        {
            $this->remove($key);
        }
        
        /**
         * ------------------------------------------------------------
         * Countable
         * ------------------------------------------------------------
         */
        
        /**
         * Restituisce il numero dei parametri
         *
         * @return int
         */
        public function count()
        {
            return count($this->data);
        }
        
        /**
         * ------------------------------------------------------------
         * IteratorAggregate
         * ------------------------------------------------------------
         */
        
        /**
         * Usa oggetto come array
         *
         * @return ArrayIterator
         */
        public function getIterator()
        {
            return new ArrayIterator($this->data);
        }
    }