<?php namespace Helpers;
    
    /**
     * Date 
     *
     * Gestisce la formattazione della data.
     *
     * l = Giorno (es. Martedi)
     * d = N° giorno (es. 01) 
     * m = N° mese (es. 01)
     * F = Mese (es. Febbraio)
     * Y = Anno (es. 2016)
     *
     * @author Davide Cesarano
     */
    
    class Date {
        
        /**
         * Crea diversi formati di date 
         *
         * @param date $date es. 'Y-m-d'
         * @param string $output es. 'l, d F Y'
         * @return string
         */
        public static function create($date, $output){
            
            // output
            switch($output){
                case 'l, d F Y':
                    return ucwords(strftime("%A, %d %B %Y", strtotime($date)));
                    break;
                case 'l, d F Y, H:i':
                    return ucwords(strftime("%A, %d %B %Y, %H:%M", strtotime($date)));
                    break;
                case 'l, d F Y alle H:i':
                    return strftime("%A, %d %B %Y alle %H:%M", strtotime($date));
                    break;
                case 'd F Y':
                    return strftime("%d %B %Y", strtotime($date));
                    break;
                case 'd-m-Y':
                    return strftime("%d-%m-%Y", strtotime($date));
                    break;
                case 'Y-m-d':
                    return strftime("%Y-%m-%d", strtotime($date));
                    break;
                case 'H:i':
                    return strftime("%H:%M", strtotime($date));
                    break;
                default:
                    return $date;
            }
            
        }
        
    }
