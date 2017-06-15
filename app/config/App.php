<?php 
    
    /**
     * Configurazioni generali dell'applicazione
     *
     * @author Davide Cesarano
     */
    
    return [
        
        'app' => [
            
            /**
             * Configurazioni: files|database
             */
            'driver' => 'files',
            
            /**
             * Temi
             */
            'template' => [
                'public' => 'default',
                'admin'  => 'default'
            ],
            
            /**
             * Meta tag
             */
            'meta' => [
                'title'       => 'Embryo',
                'keywords'    => '',
                'description' => '',
                'language'    => 'it',
                'charset'     => 'utf-8',
                'robots'      => 'index, follow',
                'author'      => ''
            ],
            
            /**
             * Traduzioni 
             */
            'languages' => [
                'it' => 'Italiano',
                'en' => 'Inglese'
            ],
            
            /**
             * Time
             */
            'time' => [
                'zone'   => 'Europe/Rome',
                'locale' => 'ita',
                'encode' => 'it_IT.utf8'
            ]
        
        ]
    
    ];