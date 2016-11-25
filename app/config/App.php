<?php 
    
    /**
     * Configurazioni generali dell'applicazione
     *
     * @author Davide Cesarano
     */
     
    use Core\Config;
    
    Config::set('app', array(
        
        /**
         * Temi
         */
        'template' => array(
            'public' => 'default',
            'admin'  => 'default'
        ),
        
        /**
         * Meta tag
         */
        'meta' => array(
            'title'       => 'Embryo',
            'keywords'    => '',
            'description' => '',
            'language'    => 'it',
            'charset'     => 'utf-8',
            'robots'      => 'index, follow',
            'author'      => ''
        ),
        
        /**
         * Timezone
         */
        'timezone' => 'Europe/Rome',
        
        /**
         * Modalità "in manutenzione"
         */
        'maintenance' => 0
        
    ));