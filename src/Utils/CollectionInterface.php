<?php 
    
    /**
     * CollectionInterface 
     */

    namespace Embryo\Utils;
    
    use ArrayAccess;
    use IteratorAggregate;
    use Countable;
    
    interface CollectionInterface extends ArrayAccess, Countable, IteratorAggregate 
    {
        public function all();
        public function keys(array $parameters = []);
        public function append(array $parameters = []);
        public function get($key, $default = null);
        public function set($key, $value);
        public function has($key);
        public function remove($key);
        public function clear();
    }