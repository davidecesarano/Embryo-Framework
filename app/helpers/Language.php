<?php namespace Helpers;

    /**
     * Language
     *
     * @author Davide Cesarano
     */

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
            $locale = main_language();

            // imposta sessione
            if(!self::get()){
                Session::set('lang', $locale);
            }
            
            // codice per codifica
            $code = self::get().'_'.strtoupper(self::get());
            
            // codifica
            $encode = $code.'_utf8';
            
            // crea messaggi
            self::setMessages(self::get());

            // lingua per le date
            setlocale(LC_TIME, $locale, $encode);

        }

        /**
         * Setta lingua
         *
         * @param string $lang
         */
        public static function set($lang){

            // crea sessione
            Session::set('lang', $lang);
            
            // codice per codifica
            $code = self::get().'_'.strtoupper(self::get());
            
            // codifica
            $encode = $code.'_utf8';
            
            // crea messaggi
            self::setMessages($lang);

            // lingua per le date
            setlocale(LC_TIME, $lang, $encode);

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
