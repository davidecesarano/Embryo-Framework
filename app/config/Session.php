<?php 
    
    /**
     * Configurazioni per le sessioni
     *
     * Il driver può essere di tipo:
     * - files
     * - database
     *
     * @author Davide Cesarano
     */
     
    return [
        
        'session' => [
            'driver'    => 'files',
            'name'      => 'embryo_sess',
            'timelife'  => 3600,
            'table'     => ''
            
        ]
        
    ];