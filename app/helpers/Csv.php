<?php namespace Helpers;
	
    /**
     * Csv
     *
     * Importa dati da un file .csv
     *
     * @author Davide Cesarano
     */
	
    class Csv {
        
        /**
         * Apre il file in lettura posizionando
         * puntatore all'inizio
         *
         * @var mixed $fp
         */
        private $fp;
        
        /**
         * Se true attiva la modalità fgetcsv 
         *
         * @var boolean $parse_header
         */
        private $parse_header;
       
        /**
         * Analizza il file in cerca di
         * campi csv
         *
         * @var mixed $header
         */
        private $header;
        
        /**
         * Delimitatore di campo
         *
         * @var string $delimiter
         */
        private $delimiter;
        
        /**
         * Numero di righe da estrapolare
         *
         * @var int $length
         */
        private $length;
        
        /**
         * Apre il file
         *
         * @param string    $file
         * @param boolean   $parse_header
         * @param string    $delimiter
         * @param int       $length
         */
        function __construct($file_name, $parse_header = false, $delimiter = "\t", $length = 5000){
            
            $this->fp               = fopen($file_name, "r");
            $this->parse_header     = $parse_header;
            $this->delimiter        = $delimiter;
            $this->length           = $length;
            if($this->parse_header) $this->header = fgetcsv($this->fp, $this->length, $this->delimiter);

        }
        
        /**
         * Chiude file 
         */
        function __destruct(){
            if($this->fp) fclose($this->fp);
        }
    
        /**
         * Estrae dati
         * 
         * @param  int   $max_lines - se 0 estrae tutto
         * @return array $data
         */
        function get($max_lines = 0){
       
            $data = array();
            $line_count = ($max_lines > 0) ? 0 : -1;

            while($line_count < $max_lines && ($row = fgetcsv($this->fp, $this->length, $this->delimiter)) !== FALSE){
                
                if($this->parse_header){
                    
                    foreach ($this->header as $i => $heading_i){
                        $row_new[$heading_i] = $row[$i];
                    }
                    $data[] = $row_new;
                
                }else{
                    $data[] = $row;
                }

                if($max_lines > 0) $line_count++;
                
            }
            return $data;
        
        }
	
    } 