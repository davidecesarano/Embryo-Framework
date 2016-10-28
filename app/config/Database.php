<?php 
    
    /**
     * Configurazioni per i database 
     *
     * @author Davide Cesarano
     */
     
    use Core\Config;
    
    /**
     * Database
     */
    Config::set('database', array(
        
        'local' => array(
            'engine'   => 'mysql',
            'host'     => 'localhost',
            'name'     => '',
            'user'     => '',  
            'password' => ''
        )

    ));