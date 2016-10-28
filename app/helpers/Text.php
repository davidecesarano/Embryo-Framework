<?php namespace Helpers;
    
    /**
     * Esegue operazioni sui testi
     *
     * @author Davide Cesarano
     */
     
    class Text {
        
        /**
         *  
         *
         * @param string $string 
         * @param int $length 
         * @return string $excerpt
         */
        public static function shorten($string, $length){
            
            if(strlen($string) > $length){
                
                $excerpt   = substr($string, 0, $length-3);
                $lastSpace = strrpos($excerpt, ' ');
                $excerpt   = substr($excerpt, 0, $lastSpace);
                $excerpt  .= '...';
           
            }else{
                $excerpt = $string;
            }
            return $excerpt;
            
        }
        
    }