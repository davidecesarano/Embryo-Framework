<?php namespace Helpers;
    
    /**
     * Dashboard 
     *
     * @author Davide Cesarano
     */
     
    use Core\Config;
    use Helpers\Database;
    
    class Dashboard {
        
        /**
         * @var string $table 
         */
        protected static $table = 'mvc_options';
        
        /**
         * @var array $default_setting 
         */
        protected static $default_setting = array(
            'seo_title'             => 'Embryo',
            'theme_name'            => 'default',
            'theme_dashboard_name'  => 'default',
            'maintenance'           => 0
        );
        
        /**
         * Verifica l'esistenza dell'istallazione
         * della Dashboard 
         *
         * @return boolean 
         */
        public static function exists(){
            
            $table = static::$table;
            $db = new Database(Config::get('database', 'local'));
            $obj = $db->query("SHOW TABLES LIKE '$table'")->rowCount();
            return ($obj > 0) ? true : false;
            
        }
        
        /**
         * Ottiene un'impostazione dell'applicazione
         * dal database
         *
         * @param string $name 
         * @return mixed
         */
        public static function setting($name){
            
            $database = Config::get('database', 'local.name');
            $setting_class = '\Models\Dashboard\Setting';
            
            $active = false;
            if(class_exists($setting_class) && $database != ''){
                
                $model = new \Models\Dashboard\Setting;
                $option = $model->getOption($name);
                if($option){
                    
                    $active = true;
                    return $option->value;
                    
                }
                
            }
            
            if(!$active){
                
                if(array_key_exists($name, static::$default_setting)){
                    return static::$default_setting[$name];
                }else{
                    return '';
                }
                
            }
            
        }
        
    }