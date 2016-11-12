<?php 
    
    use Core\Config;
    
    $template = Config::get('app', 'template');
    
    define('SITE_THEME_PUBLIC', SITE_URL.'/'.FOLDER_THEMES.'/'.$template['public']);
    define('FOLDER_TEMPLATE_PUBLIC', FOLDER_PUBLIC.'/'.$template['public']);
    define('SITE_DASHBOARD', SITE_URL.'/'.FOLDER_DASHBOARD.'/'.$template['admin']);
    define('FOLDER_TEMPLATE_DASHBOARD', FOLDER_DASHBOARD.'/'.$template['admin']);