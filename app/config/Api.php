<?php 
    
    /**
     * Configurazioni per le api
     *
     * @author Davide Cesarano
     */
     
    use Core\Config;
    
    /**
     * API
     */
    Config::set('api', array(
        
        /**
         * Google Analytics
         */
        'ga' => array(
            'profile_id'      => '',
            'service_account' => '',
            'service_key'     => ''
        ),
        
        /**
         * Facebook
         */
        'facebook' => array(
            'app_id' => ''
        )
        
    ));