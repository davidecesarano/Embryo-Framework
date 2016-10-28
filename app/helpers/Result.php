<?php namespace Helpers;

	/**
     * Result
     *
     * Si occupa di stampare l'output di una richiesta.
     *
     * @author Davide Cesarano
     */
     
    class Result {
		
        /**
         * Stampa output 
         *
         * @param mixed $data
         * @return mixed
         */
		public static function get($data){
			
			if(!is_array($data)){
				
                echo $data;
				
			}else{
				
                foreach($data as $value){
					echo $value;
				}
			
            }
			exit;
		
		}
        
        /**
         * Stampa output in formato JSON
         *
         * @param mixed $data
         * @return json
         */
        public static function json($data){
            
            header('Content-Type: application/json');
			print json_encode($data, JSON_PRETTY_PRINT);
            
        }
		
	}