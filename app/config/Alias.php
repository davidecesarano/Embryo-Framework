<?php  
    
    /**
     * Configurazioni per l'alias di
     * classi e/o percorsi
     *
     * @author Davide Cesarano
     */
    
    use Core\Config;
    
    Config::set('alias', array(
        
        /**
         * Helpers
         */
        'helpers' => array(
            'Date'    => 'Helpers\Date',
            'Session' => 'Helpers\Session',
            'Token'   => 'Helpers\Token',
            'Widget'  => 'Helpers\Widget'
        )
    
    ));