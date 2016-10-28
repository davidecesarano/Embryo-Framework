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
         * Assets delle dipendenze
         */
        'assets' => array(
            'bootstrap'   => 'bootstrap',
            'datatables'  => 'datatables/media',
            'fontawesome' => 'font-awesome',
            'jquery'      => 'jquery',
            'moment'      => 'moment',
            'tinymce'     => 'tinymce'
        ),
        
        /**
         * Helpers
         */
        'helpers' => array(
            'Asset'   => 'Helpers\Asset',
            'Date'    => 'Helpers\Date',
            'Session' => 'Helpers\Session',
            'Text'    => 'Helpers\Text',
            'Token'   => 'Helpers\Token',
            'Widget'  => 'Helpers\Widget'
        )
    
    ));