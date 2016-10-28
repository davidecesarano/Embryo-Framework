<?php 
    
    /**
     * Configurazioni per le E-Mail 
     *
     * @author Davide Cesarano 
     */
    
    use Core\Config;
    use Helpers\Dashboard;
    
    Config::set('email', array(
        
        /**
         * Administrator 
         */
        'administrator' => array(
            'host'     => '',
            'port'     => '',
            'username' => '',
            'password' => ''
        )
    
    ));