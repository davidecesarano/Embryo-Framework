<?php namespace Core;
    
    /**
     * Handler delle Sessioni 
     *
     * Imposta le funzioni di archiviazione delle sessioni.
     * E' possibile scegliere un'archiviazione mediante files 
     * oppure mediante database.
     *
     * @author Davide Cesarano 
     */
    use \Exception;
    use Core\Config;
    use Helpers\Database;
    
    class Session {
        
        /**
         * @var mixed $config 
         */
        protected $config;
        
        /**
         * @var object $db 
         */
        protected $db;
        
        /**
         * @var string $table 
         */
        protected $table;
        
        /**
         * @var string $path 
         */
        protected $path;
        
        /**
         * Avvia handler e imposta parametri
         */
        public function __construct(){
            
            // configurazioni
            $this->config = Config::get('session');
            
            // cartella
            $this->path = SITE_BASE_DIR.'/'.FOLDER_SESSIONS;
            
            // verifiche sulle configurazioni
            $this->init();
            
            // salvataggio funzioni handler
            session_set_save_handler(
                array($this, 'open'),
                array($this, 'close'),
                array($this, 'read'),
                array($this, 'write'),
                array($this, 'destroy'),
                array($this, 'gc')
            );
            
            // imposta parametri da php.ini
            ini_set('session.use_strict_mode',          1);
            ini_set('session.auto_start',               0);
            ini_set('session.gc_probability',           1);
            ini_set('session.gc_divisor',               100);
            ini_set('session.save_path',                $this->path);
            ini_set('session.gc_maxlifetime',           $this->config['timelife']);
            ini_set('session.use_cookies',              1);
            ini_set('session.use_only_cookies',         1);
            ini_set('session.use_trans_sid',            0);
            ini_set('session.cookie_httponly',          1);

            // imposta durezza id di sessione
            if(version_compare(PHP_VERSION, '7.1', '<')){
                
                // PHP < 7.1
                ini_set('session.entropy_file', '/dev/urandom');
                ini_set('session.entropy_length', 128);
                ini_set('session.hash_function', 'sha512');

            } else {
                
                // PHP >= 7.1
                ini_set('session.sid_length', 128);
            
            }
            
            // disabilita client/proxy caching
            session_cache_limiter('nocache');
            
            // parametri cookie 
            $timelife = $this->config['timelife'];
            $path     = '/';
            $domain   = $_SERVER['SERVER_NAME'];
            $secure   = (SITE_PROTOCOL === 'https://') ? true : false;
            $httponly = true;
             
            // imposta parametri del cookie
            session_set_cookie_params($timelife, $path, $domain, $secure, $httponly);
            
            // imposta nome della sessione
            session_name($this->config['name']);
            
            // imposta funzione in caso di errore
            register_shutdown_function('session_write_close');
        
        }
        
        /**
         * Effettua verifiche sul driver 
         * di gestione delle sessioni
         */
        private function init(){
            
            if($this->config['driver'] == 'files'){
                
                if(!is_dir($this->path)){
                    throw new Exception("Configurazione non valida!");
                }else{
                    return true;
                }
                
            }elseif($this->config['driver'] == 'database'){
                
                $this->db = Database::connection();
                $this->table = $this->config['table'];
                
            }else{
                throw new Exception("Configurazione non valida!");
            }
            
        }
        
        /**
         * Apre sessione
         *
         * @param string $path
         * @param string $name
         * @return boolean 
         */
        public function open($path, $name){
            
            if($this->config['driver'] == 'database'){
                
                $limit = time() - ($this->config['timelife'] * 24);
                $this->db->query("
                    DELETE FROM $this->table
                    WHERE timestamp < :timestamp
                ")->value(
                    'timestamp', $limit
                )->execute();
                
            }
            return true;
            
        }
        
        /**
         * Chiude sessione
         *
         * @return boolean 
         */
        public function close(){
            
            if($this->config['driver'] == 'database'){
                $this->db->close();
            }
            return true;
            
        }
        
        /**
         * Legge sessione
         *
         * @param int $id
         * @return string|boolean 
         */
        public function read($id){
            
            if($this->config['driver'] == 'files'){
                
                $file = $this->path.'/'.$this->config['name'].'_'.$id;
                if(file_exists($file)){
                    return file_get_contents($file);
                }else{
                    return '';
                }
                
            }elseif($this->config['driver'] == 'database'){
            
                $obj = $this->db->query("
                    SELECT data FROM $this->table 
                    WHERE id = :id
                ")->value(
                    'id', $id
                )->single();
                
                return ($obj) ? $obj->data : '';
                
            }
        
        }
        
        /**
         * Scrive sessione
         *
         * @param int $id
         * @param string $data
         * @return boolean 
         */
        public function write($id, $data){

            if($this->config['driver'] == 'files'){
            
                $file = $this->path.'/'.$this->config['name'].'_'.$id;
                return file_put_contents($file, $data) === false ? false : true;
                
            }elseif($this->config['driver'] == 'database'){
                
                $this->db->query("
                    REPLACE INTO $this->table (
                        id,
                        data,
                        timestamp
                    ) VALUES (
                        :id,
                        :data,
                        :timestamp
                    )
                ")->values(array(
                    'id' => $id,
                    'data' => $data, 
                    'timestamp' => time()
                ))->execute();
                
            }
            return true;
        
        }
        
        /**
         * Distrugge sessione
         *
         * @param int $id
         * @return boolean 
         */
        public function destroy($id){
            
            if($this->config['driver'] == 'files'){
                
                $file = $this->path.'/'.$this->config['name'].'_'.$id;
                if(file_exists($file)){
                    @unlink($file);
                }
            
            }elseif($this->config['driver'] == 'database'){
                
                $this->db->query("
                    DELETE FROM $this->table
                    WHERE id = :id
                ")->value(
                    'id', $id
                )->execute();
                
            }
            return true;
        
        }
        
        /**
         * Routine cestinaggio sessione
         *
         * @param int $maxlifetime
         * @return boolean 
         */
        public function gc($maxlifetime){
            
            $maxlifetime = $this->config['timelife'];
            
            if($this->config['driver'] == 'files'){
            
                $files = $this->path.'/'.$this->config['name'].'_*';
                foreach(glob($files) as $file){
                    if(filemtime($file) + $maxlifetime < time() && file_exists($file)){
                        unlink($file);
                    }
                }
                
            }elseif($this->config['driver'] == 'database'){
                
                $timestamp = time() - intval($maxlifetime);
                $this->db->query("
                    DELETE FROM $this->table
                    WHERE timestamp < :timestamp
                ")->value(
                    'timestamp', $timestamp
                )->execute();
                
            }
            return true;
            
        }
        
        /**
         * Termina sessione e archivia dati
         */
        public function __destruct(){
            session_write_close();
        }
        
    }