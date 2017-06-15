<?php namespace Middleware;
	
    /**
     * Language 
     *
     * @author Davide Cesarano
     */
    
    use Helpers\Language as setLanguage; 
    
	class Language {
		
        /**
         * Setta messaggi in base alla lingua 
         *
         * @param string $code 
         * @return array
         */
		public function index($code){
            setLanguage::set($code);
        }
		
	}