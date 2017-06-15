<?php 
    
    /**
     * Configurazioni per le routes dei
     * middlewares
     *
     * @author Davide Cesarano 
     */
    
    return [ 
        
        'middlewares' => [
            'Token:name'    => 'Token@index',
            'Language:code' => 'Language@index'
        ]
        
    ];