<?php namespace Helpers;

    /**
     * Language
     *
     * @author Davide Cesarano
     */

    use Core\Config;
    use Helpers\Directory;
    use Helpers\Session;

    class Language {

        /**
         * @var array $messages
         */
        public static $messages = array();
        
        /**
         * Inizializza lingua 
         */
        public static function init(){

            // lingua di default
            $default_language = Config::get('app', 'meta.language');

            // imposta sessione
            if(!self::get()){
                Session::set('lang', $default_language);
            }
            
            // codice per codifica
            $code = self::get().'_'.strtoupper(self::get());
            
            // crea messaggi
            self::setMessages(self::get());

            // lingua per le date
            setlocale(LC_TIME, "$code.utf8");

        }

        /**
         * Setta lingua
         *
         * @param string $lang
         */
        public static function set($lang){

            // crea sessione
            Session::set('lang', $lang);

            // crea messaggi
            self::setMessages($lang);
            
            // codice per codifica
            $code = self::get().'_'.strtoupper(self::get());

            // lingua per le date
            setlocale(LC_TIME, "$code.utf8");

        }

        /**
         * Ottiene il valore della lingua impostata
         *
         * @return string|boolean
         */
        public static function get(){
            return (Session::exists('lang')) ? Session::get('lang') : false;
        }

        /**
         * Genera messaggi
         *
         * @param string $lang
         */
        public static function setMessages($lang){
			
            if(Directory::is_empty(FOLDER_LANGUAGES.'/'.$lang.'/')){

                $array = array();
                foreach(glob(FOLDER_LANGUAGES.'/'.$lang.'/*.php') as $file){
                    $array += include $file;
                }
                self::$messages = $array;

            }

        }

        /**
         * Ottiene il valore di un messaggio
         *
         * @param string $key
         * @return string
         */
        public static function getMessage($key){
            return self::$messages[$key];
        }

        /**
         * Ottiene tutti i messaggi
         *
         * @param string $lang
         * @return array
         */
        public static function getMessages($lang){

            self::setMessages($lang);
            return self::$messages;

        }

    }
