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
             * Timezone
             */
            'timezone' => 'Europe/Rome',
            
            /**
             * Lingua
             */
            'locale' => 'it'
        
        ]
    
    ];