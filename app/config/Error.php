<?php 
    
    /**
     * Configurazioni per errori
     *
     * @author Davide Cesarano
     */
     
    use Core\Config;
    use Helpers\Dashboard;
    
    Config::set('error', array(
        'email'    => false,
        'email_to' => '',
        'display'  => true,
        'debug'    => 'development'
    ));