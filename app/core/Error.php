<?php namespace Core;
    
    /**
     * Error 
     * 
     * Cattura gli errori dell'applicazione
     * ed esegue una serie di funzioni.
     *
     * @author Davide Cesarano
     */
    
    use Core\Config;
    use Core\Controller;
    use Helpers\Mail;
    
    class Error {		
        
        /**
         * Messaggio di errore
         *
         * @param string $text
         * @return string
         */
        public static function message($text = null){
            
            if(!$text || Config::get('app', 'errors.display') === false){
                echo "<h1>Si &egrave; verificato un errore!</h1>";
            }else{
                echo '<p>'.$text.'</p>';
            }
        
        }
        
        /**
         * Scrive il report dell'errore
         * in un file di log 
         *
         * @param string self::$file
         * @param string $log 
         */
        public static function write($log){
            
            $name = date('Y_m_d').'.log';
            $file = SITE_BASE_DIR.'/'.FOLDER_LOGS.'/'.$name;
            
            if(file_exists($file)){
                
                // aggiorrna
                $contents = explode("\n", file_get_contents($file));
                $contents[0] = $log;
                file_put_contents($file, implode($contents, "\n"));
                
            }else{
                
                // crea
                touch($file);
                chmod($file, 0777);
                file_put_contents($file, $log);
                
                // email
                if(Config::get('app', 'errors.email') === true) self::sendEmail($log);
                
            }
            
        }
        
        /**
         * Gestione delle Exception
         *
         * @param exception $exception
         * @param boolean self::$page
         * @param boolean self::$display
         * @return mixed
         */
        public static function exceptionHandler($exception){
            
            // report
            $report = self::exceptionReport($exception);
            
            // log
            self::write($report);
            
            // messaggio
            self::message($exception->getMessage());
            
        }
        
        /**
         * Report per le Exception
         *
         * @param exception $exception
         * @return string $log
         */
        public static function exceptionReport($exception){
            
            // errore
            $message = $exception->getMessage();
            $code = $exception->getCode();
            $code = $exception->getCode();
            $file = $exception->getFile();
            $line = $exception->getLine();
            $trace = $exception->getTraceAsString();
            $trace = str_replace(Config::get('database', 'local.password'), '****', $trace);
            $trace = str_replace(Config::get('database', 'local.host'), '****', $trace);
            $trace = str_replace(Config::get('database', 'local.user'), '****', $trace);
            $trace = str_replace(Config::get('database', 'local.name'), '****', $trace);
            $date = date('d M, Y - H:i:s');
            
            // log
            $log = "\nData: {$date}\n";
            $log .= "Handler: Exception\n";
            $log .= "Descrizione: {$message}\n";
            $log .= "Codice: {$code}\n";
            $log .= "File: {$file}\n";
            $log .= "Linea: {$line}\n";
            $log .= "Tracciato:\n";
            $log .= $trace;
            $log .= "\n--------------------\n";  
            return $log;
            
        }
        
        /**
         * Getione degli Errori
         *
         * @param exception $exception
         * @return mixed
         */
        public static function errorHandler($type, $description, $filename, $line){
            
            switch($type){
                
                case E_WARNING:
                case E_NOTICE:
                case E_USER_ERROR:
                case E_USER_WARNING:
                case E_USER_NOTICE:
                case E_RECOVERABLE_ERROR:
                case E_ALL:
                    
                    // nome errore
                    $typename = self::errorType($type);
                    
                    // report
                    $report = self::errorReport($typename, $description, $filename, $line);
                    
                    // log
                    self::write($report);

                    // messaggio
                    self::message($typename.' - '.$description);
                    
            }			
            
        }
        
        /**
         * Report per gli Errori
         *
         * @param string $typename
         * @param string $description
         * @param string $filename
         * @param int $line 
         * @return string $log
         */
        public static function errorReport($typename, $description, $filename, $line){
            
            // data 
            $date = date('d M, Y - H:i:s');
            
            // log
            $log = "\nData: $date\n";
            $log .= "Handler: Error\n";
            $log .= "Tipologia: $typename\n";
            $log .= "Descrizione: $description\n";
            $log .= "File: $filename\n";
            $log .= "Linea: $line\n";
            $log .= "--------------------\n";  
            return $log;
            
        }
        
        /**
         * Gestisce gli errori di tipo FATAL
         *
         * @param boolean self::$display
         * @return mixed
         */
        public static function shutdownHandler(){
            
            // errore
            $error = error_get_last();
            
            switch($error['type']){
                
                case E_ERROR:
                case E_PARSE:
                case E_CORE_ERROR:
                case E_CORE_WARNING:
                case E_COMPILE_ERROR:
                case E_COMPILE_WARNING:
                case E_STRICT:
                
                    // report errore
                    $report = self::shutdownReport($error);
                    
                    // log
                    self::write($report);
                    
                    // messaggio
                    self::message(self::errorType($error['type']).' - '.$error['message']);
                
            }
            
        }
        
        /**
         * Report per gli errori di tipo FATAL
         *
         * @param array $error 
         * @return string $log
         */
        public static function shutdownReport($error){
            
            // data 
            $date = date('d M, Y - H:i:s');
            
            // errore
            $type = $error['type'];
            $typename = self::errorType($error['type']);
            $message = $error['message'];
            $file = $error['file'];
            $line = $error['line'];				
                    
            // log
            $log = "\nData: $date\n";
            $log .= "Handler: Shutdown\n";
            $log .= "Tipologia: $typename\n";
            $log .= "Descrizione: $message\n";
            $log .= "File: $file\n";
            $log .= "Linea: $line\n";
            $log .= "--------------------\n";  
            return $log;
        
        }
        
        /**
         * Tipologia errore 
         *
         * @param string $type 
         * @return string
         */
        public static function errorType($type){
            
            switch($type){
                case E_ERROR:
                    return 'E_ERROR';
                case E_WARNING:
                    return 'E_WARNING';
                case E_PARSE:
                    return 'E_PARSE';
                case E_NOTICE:
                    return 'E_NOTICE';
                case E_CORE_ERROR:
                    return 'E_CORE_ERROR';
                case E_CORE_WARNING:
                    return 'E_CORE_WARNING';
                case E_COMPILE_ERROR:
                    return 'E_COMPILE_ERROR';
                case E_COMPILE_WARNING:
                    return 'E_COMPILE_WARNING';
                case E_USER_ERROR:
                    return 'E_USER_ERROR';
                case E_USER_WARNING:
                    return 'E_USER_WARNING';
                case E_USER_NOTICE:
                    return 'E_USER_NOTICE';
                case E_STRICT:
                    return 'E_STRICT';
                case E_RECOVERABLE_ERROR:
                    return 'E_RECOVERABLE_ERROR';
                case E_DEPRECATED:
                    return 'E_DEPRECATED';
                case E_USER_DEPRECATED:
                    return 'E_USER_DEPRECATED';
            }
            return "";
        
        }
        
        /**
         * Invia report errore tramite email+
         *
         * @param string $log 
         */
        public static function sendEmail($log){
            
            $mail = new Mail;
            $mail->account(Config::get('email', 'administrator'));
            $mail->setFrom(Config::get('email', 'administrator.username'), 'Administrator');
            $mail->addAddress(Config::get('app', 'errors.email_to'), 'Administrator');
            $mail->Subject = 'Ops, si è verificato un errore!';
            $body = "Report errore:<br />";
            $body .= str_replace("\n", "<br />", $log);
            $mail->Body = $body;
            $mail->send();			
            
        }
        
        /**
         * Genera errore 404 
         *
         * @param string|null $msg
         */
        public static function getError404($msg = null){
            
            $controller = new Controller;
            $controller->loadError404($msg);
            
        }
        
    }