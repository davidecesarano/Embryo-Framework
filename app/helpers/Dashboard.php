<?php namespace Helpers;
    
    use Core\Config;
    use Helpers\Database;
    use Models\Dashboard\Setting;
    
    class Dashboard {
        
        protected static $table = 'mvc_options';
        
        public static function setting($name){
            
            $model = new Setting;
            $option = $model->getOption($name);
            return $option->value;
            
        }
        
        public static function exists(){
            
            $table = static::$table;
            $db = Database::get(Config::get('database', 'local'));
            $obj = $db->query("SHOW TABLES LIKE '$table'")->getRowCount();
			return ($obj > 0) ? true : false;
            
        }
        
    }