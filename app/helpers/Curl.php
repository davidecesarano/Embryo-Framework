<?php namespace Helpers;

    /**
     * Curl
     *
     * Si occupa di far comunicare script PHP con server 
     * remoti per svolgere determinate operazioni di 
     * invio e/o ricezione dati
     *
     * @author Davide Cesarano
     */
    
    class Curl {
        
        /**
         * @var string $url 
         */
        public $url;
        
        /**
         * @var mixed $ch 
         */
        public $ch;
        
        /**
         * @var mixed $response 
         */
        public $response;
        
        /**
         * Inizializza la sessione di cURL
         */
        public function __construct($url){
            
            $this->url = $url;
            $this->ch = curl_init();
            return $this;
            
        }
        
        /**
         * Imposta url della risorsa remota
         */
        public function url(){
            return curl_setopt($this->ch, CURLOPT_URL, $this->url);
        }
        
        /**
         * Invio dati mediante medoto POST
         */
        public function post(){
            return curl_setopt($this->ch, CURLOPT_POST, true);
        }
        
        /**
         * Include l'instestazione in output
         *
         * @param int $default
         */
        public function header($default = 0){
            return curl_setopt($this->ch, CURLOPT_HEADER, $default);
        }
        
        /**
         * Dati da inviare con metodo POST 
         *
         * @param array $data 
         */
        public function postfields($data = array()){
            return curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        }
        
        /**
         * Stabilisce se cURL debba stampare a video il 
         * contenuto recuperato dalla risorsa remota oppure no
         */
        public function returntransfer(){
            return curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        }
        
        /**
         * Lasso di tempo oltre il quale PHP dovrà chiudere
         * la connessione in assenza di risposta
         *
         * @param int $time
         */
        public function timeout($time){
            return curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $time);
        }
        
        /**
         * Esegue la sessione di cUrl
         *
         * @return $this 
         */
        public function exec(){
            
            $this->response = curl_exec($this->ch);  
            return $this;
            
        }
        
        /** 
         * Chiude la sessione di cUrl 
         */
        public function close(){
            return curl_close($this->ch);
        }
        
        /**
         * Decodifica la sessione di cUrl 
         *
         * @return array|object 
         */
        public function decode(){
            return json_decode($this->response);
        }
        
    }