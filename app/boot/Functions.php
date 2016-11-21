<?php 
    
    /**
     * Funzioni generali del framework.
     *
     * @author Davide Cesarano
     */
     
    use Core\Config;
    
    /**
     * Debug
     *
     * @param mixed $var 
     * @return mixed
     */

    function debug($var){
        
        echo "<pre>";
            print_r($var);
        echo "</pre>";		
        
    }
    
    /**
     * Ambiente di sviluppo
     */
    function enviroment(){
        
        switch(Config::get('app', 'debug')){
            case 'development':
                error_reporting(E_ALL);
                ini_set('display_errors', 1);
                break;
            case 'production':
                error_reporting(E_ALL);
                ini_set('display_errors', 0);
                break;
            default:
                exit("L'ambiente di sviluppo dell'applicazione non &egrave; stato impostato correttamente!");
        }
        
    }
    
     /**
     * Alias per le classi 'Helpers' utilizzate
     * nei file del template
     *
     * @throws exception
     */
    function helpers_alias(){
        
        $helpers = Config::get('alias', 'helpers');
            
        foreach($helpers as $name => $class){
            
            if(class_exists($class)){
                class_alias($class, $name);
            }else{
                throw new Exception("La classe <strong>$class</strong> non esiste!");
            }
            
        }
        
    }
    
    function maintenance(){
            
    }
    
    /**
     * Url
     *
     * @param string $path
     * @return string 
     */
    function site_url($path = null){
        return ($path) ? SITE_URL.'/'.$path : SITE_URL;
    }
    
    /**
     * Url template frontend
     *
     * @param string $path
     * @return string 
     */
    function template_url($path){
        return site_url(FOLDER_PUBLIC.'/'.Config::get('app', 'template.public').'/assets/'.$path);
    }
    
    /**
     * Url template backend
     *
     * @param string $path
     * @return string 
     */
    function template_admin_url($path = null){
        return site_url(FOLDER_DASHBOARD.'/'.Config::get('app', 'template.admin').'/assets/'.$path);
    }
    
    /**
     * Path template frontend
     *
     * @param string $path
     * @return string 
     */
    function folder_views_template($path){
        return FOLDER_PUBLIC.'/'.Config::get('app', 'template.public').'/views/'.$path;
    }
    
    /**
     * Path template backend
     *
     * @param string $path
     * @return string 
     */
    function folder_views_template_admin(){
        return FOLDER_DASHBOARD.'/'.Config::get('app', 'template.admin').'/views/'.$path;
    }
    
    /**
     * Meta-tag Title
     *
     * @return string
     */
    function title(){
        return Config::get('app', 'meta.title');
    }
    
    /**
     * Meta-tag Keywords
     *
     * @return string
     */
    function keywords(){
        return Config::get('app', 'meta.keywords');
    }
    
    /**
     * Meta-tag Description 
     *
     * @return string
     */
    function description(){
        return Config::get('app', 'meta.description');
    }
    
    /**
     * Meta-tag Language 
     *
     * @return string
     */
    function meta_language(){
        return Config::get('app', 'meta.language');
    }
    
    /**
     * Meta-tag Charset 
     *
     * @return string
     */
    function meta_charset(){
        return Config::get('app', 'meta.charset');
    }
    
    /**
     * Redirect 
     *
     * @param string $page
     */
    function redirect($page){
        
        header('Location: '.site_url($page));
		exit;
        
    }
    
    /**
     * JSON 
     *
     * @param array $data
     * @return json
     */
    function json($data){
            
        header('Content-Type: application/json');
        print json_encode($data, JSON_PRETTY_PRINT);
        
    }