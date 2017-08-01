<?php namespace Helpers;

    /** 
     * File 
     *
     * @author Davide Cesarano
     */
    
    use Helpers\ArrObj;
    
    class File {
        
        /**
         * @var array $file
         */
        public $file;
        
        /**
         * @var string $name
         */
        public $name;
        
        /**
         * @var string $destination
         */
        public $destination;
        
        /**
         * Setta proprietà per operazioni preliminari
         *
         * @param array $file 
         */
        public function __construct($file){
            $this->file = $file;
        }
        
        /**
         * Cartella di destinazione del file 
         *
         * @param string $destination
         */
        public function destination($destination){
            
            $this->destination = SITE_BASE_DIR.'/'.$destination;
            return $this;
        
        }
        
        /**
         * Estensione del file 
         *
         * @param string $name
         * @return string
         */
        public function getExtension($name){
            return '.'.pathinfo($name, PATHINFO_EXTENSION);
        }
        
        /**
         * Rimuove estensione del file 
         *
         * @param string $extension 
         * @param string $name 
         * @return string 
         */
        public function removeExtension($extension, $name){
            return str_replace($extension, '', $name);
        }
        
        /**
         * Elimina e sostituisce alcuni caratteri dal
         * nome del file 
         *
         * @param string $name 
         * @return string 
         */
        public function checkName($name){
            
            if($name != ''){
            
                // operazioni sul nome del file
                $extension = $this->getExtension($name);
                $name = $this->removeExtension($extension, $name);
                $name = str_replace(' ', '-', $name);
                $name = str_replace('.', '-', $name);
                $name = preg_replace('/[^A-Za-z0-9\-_]/', '', $name);
                
                // controllo esistenza
                $file_path = $this->destination.'/'.$name.$extension;
                
                return (!file_exists($file_path)) ? $name.$extension : $name.'_'.rand().$extension;
            
            }else{
                return '';
            }
            
        }
        
        /**
         * Upload
         *
         * @return boolean
         */
        public function upload(){
            
            $arr = new ArrObj($this->file);
            
            if($arr->is_multi()){

                for($i=0; $i<=count($this->file['name']); $i++){
                    
                    $name = $this->checkName($this->file['name'][$i]);
                    if($name != ''){
                        move_uploaded_file($this->file['tmp_name'][$i], $this->destination.'/'.$name);
                    }
                    
                }
                
            }else{
                
                $name = $this->checkName($this->file['name']);
                $this->name = $name;
                if($name != ''){
                    move_uploaded_file($this->file['tmp_name'], $this->destination.'/'.$name);
                }
                
            }
        
        }
        
    }