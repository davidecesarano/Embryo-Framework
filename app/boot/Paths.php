<?php 
    
    /**
     * ------------------------------------------------------------
     * PATHS
     *
     * @const SITE_PROTOCOL
     * @const SITE_FOLDER
     * @const SITE_URL
     * @const SITE_BASE_DIR
     * @const DOCUMENT_ROOT
     * @const CURRENT_PAGE
     * ------------------------------------------------------------
     */
    define('SITE_PROTOCOL', ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://");
    define('SITE_FOLDER', (dirname($_SERVER['PHP_SELF']) == '\\' || dirname($_SERVER['PHP_SELF']) == '/') ? '' : dirname($_SERVER['PHP_SELF']));
    define('SITE_PORT', $_SERVER['SERVER_PORT']);
    define('SITE_URL', (SITE_PORT != 80 && SITE_PORT != 443) ? rtrim(SITE_PROTOCOL.$_SERVER['SERVER_NAME'].':'.SITE_PORT.SITE_FOLDER, '/') : rtrim(SITE_PROTOCOL.$_SERVER['SERVER_NAME'].SITE_FOLDER, '/'));
    define('SITE_BASE_DIR', getcwd());
    define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
    define('CURRENT_PAGE', SITE_PROTOCOL.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);