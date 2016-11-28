<?php 
    
    /**
     * Configurazioni per le sessioni
     *
     * @author Davide Cesarano
     */
     
    use Core\Config;
    
    Config::set('session', array(
        'driver'    => 'files',
        'name'      => 'embryo_sess',
        'path'      => SITE_BASE_DIR.'/'.FOLDER_SESSIONS,
        'timelife'  => 3600,
        'table'     => 'mvc_registry_sessions'
    ));