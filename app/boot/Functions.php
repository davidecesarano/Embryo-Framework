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
        
        switch(Config::get('error', 'debug')){
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
        return site_url(FOLDER_TEMPLATE_PUBLIC.'/'.Config::get('app', 'template.public').'/assets/'.$path);
    }
    
    /**
     * Url template backend
     *
     * @param string $path
     * @return string 
     */
    function template_admin_url($path){
        return site_url(FOLDER_TEMPLATE_ADMIN.'/'.Config::get('app', 'template.admin').'/assets/'.$path);
    }
    
    /**
     * Path template frontend
     *
     * @param string $path
     * @return string 
     */
    function template_folder($path){
        return FOLDER_TEMPLATE_PUBLIC.'/'.Config::get('app', 'template.public').'/views/'.$path;
    }
    
    /**
     * Path template backend
     *
     * @param string $path
     * @return string 
     */
    function template_admin_folder($path){
        return FOLDER_TEMPLATE_ADMIN.'/'.Config::get('app', 'template.admin').'/views/'.$path;
    }
    
    /**
     * Url cartella dipendenze
     *
     * @param string $path
     * @return string 
     */
    function vendor_url($path){
        return site_url(FOLDER_VENDOR_ASSETS.'/'.$path);
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
     * Redirect dominio esterno
     *
     * @param string $url
     */
    function redirect_to($url){
        
        header('Location: '.$url);
        exit;
        
    }
    
    /**
     * Riconosce se si tratta di una pagina
     * interna o di un link esterno e restituisce
     * il relativo valore 
     *
     * @param string $path 
     * @return string
     */
    function to_url($path){
        
        if(strpos($path, '//') === false){
            return site_url($path);
        }else{
            return $path;
        }
        
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
    
    /**
     * Crea una stringa criptata di 64 caratteri
     *
     * @param string $string
     * @param string $salt
     * @return string
     */
    function hash_crypt($string, $salt){
        
        $ctx = hash_init('sha256', HASH_HMAC, $salt);
        hash_update($ctx, $string);
        return hash_final($ctx);
        
    }
    
    /** 
     * Crea una stringa di 32 caratteri
     *
     * @return string
     */
    function random_string(){
        return bin2hex(openssl_random_pseudo_bytes(16));
    }
    
    /**
     * Estrapola un tot di parole da una stringa  
     *
     * @param string $string 
     * @param int $length 
     * @return string $excerpt
     */
    function shorten($string, $length){
            
        if(strlen($string) > $length){
            
            $excerpt   = substr($string, 0, $length-3);
            $lastSpace = strrpos($excerpt, ' ');
            $excerpt   = substr($excerpt, 0, $lastSpace);
            $excerpt  .= '...';
       
        }else{
            $excerpt = $string;
        }
        return $excerpt;
        
    }