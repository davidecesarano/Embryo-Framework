<?php 
    
    /**
     * AbstractErrorHandler
     */

    namespace Embryo\Error;
    
    abstract class AbstractErrorHandler 
    {    
        /**
         * @var bool $display
         */
        protected $display = true;

        /**
         * Setta display
         */
        public function __construct($display)
        {
            $this->display = $display;
        }
        
    }