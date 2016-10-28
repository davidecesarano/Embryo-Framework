<?php namespace Helpers;
    
    use TCPDF;
    use Helpers\Asset;
    
    class PDF extends TCPDF {
        
        public function Header(){
        
            $image_file = 'http://www.unipegaso.it/website/images/logo.jpg';
            $this->Image($image_file, 10, 5, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            $this->SetFont('helvetica', 'B', 10);
            $this->MultiCell($w=140, $y=0, 'Programma del corso', $border=0, $align='L', $fill=false, $ln=1, $x='30', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false);
            $this->SetFont('helvetica', 'B', 8);
            $this->MultiCell($w=140, $y=0, "Università Telematica Pegaso", $border=0, $align='L', $fill=false, $ln=1, $x='30', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false);

            if(!empty($this->logo_2)){
                $this->Image($this->logo_2, $x='185', $y='3', $w=15, $h='', $type='JPG', $link='', $align='R', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false, $alt=false, $altimgs=array());
            }

        }   

        public function Footer() {
            
            $this->SetY(-15);
            $this->SetFont('helvetica', 'I', 7);
            $this->Cell(0, 10, 'Pagina '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        }
    
        public function cleanFromWord($string){
            
            $string = utf8_decode($string);
            $string = str_replace("&nbsp;", " ", $string);
            $string = preg_replace("/\s+/", " ", $string);
            $string = str_replace('<div>','<br/>',$string);
            $string = str_replace('</div>', '',$string);
            $string = str_replace('<p><br>', '<p>',$string);
            $string = str_replace('<p><br />','<p>',$string);
            $string = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $string);
            $string = preg_replace('/(<[^>]+) class=".*?"/i', '$1', $string);
            $string = preg_replace('|style="(.*?)"|i','',$string);
            $string = preg_replace('/(<[^>]*) style=("[^"]+"|\'[^\']+\')([^>]*>)/i', '$1$3', $string);
            $string = preg_replace("/<p[^>]*>[\s|&nbsp;]*<\/p>/", '', $string);    
            $string = preg_replace(array('/^\s\s+/', '/\s\s+$/', '/\s\s+/u'), array('', '', ' '), $string);
            $string = trim($string);
            return $string;
            
        }
        
        public function stripWordHtml($text, $allowed_tags = '<li><ol><ul>'){
            
            mb_regex_encoding('UTF-8');
            //sostituzione MS caratteri speciali (Microsoft)
            $search = array('/&lsquo;/u', '/&rsquo;/u', '/&ldquo;/u', '/&rdquo;/u', '/&mdash;/u');
            $replace = array('\'', '\'', '"', '"', '-');
            $text = preg_replace($search, $replace, $text);
            //assicurarsi che tutti le entità HTML vengono convertiti in equivalenti ASCII semplici 
            //in alcune intestazioni MS (MS Word), alcune entità HTML sono codificate e alcuni non lo sono
            $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
            //Elimino eventuali commenti in stile C prima, dal momento che questi, sono incorporati in html e sembra
            //evitino lo strip_tags  (MS Word introduced combination)
            if(mb_stripos($text, '/*') !== FALSE){
                $text = mb_eregi_replace('#/\*.*?\*/#s', '', $text, 'm');
            }
            //introduco un spazio in un espressioni aritmetiche che potrebbero essere catturate da strip_tags in modo che non lo saranno
            //'<1' diventa '< 1'
            $text = preg_replace(array('/<([0-9]+)/'), array('< $1'), $text);
            $text = strip_tags($text, $allowed_tags);
            //elimino spazi estranei da inizio e fine della linea, o dovunque vi sono due o più spazi,
            $text = preg_replace(array('/^\s\s+/', '/\s\s+$/', '/\s\s+/u'), array('', '', ' '), $text);
            //butto fuori i CSS in linea e semplificando i tag di stile
            $search = array('#<(strong|b)[^>]*>(.*?)</(strong|b)>#isu', '#<(em|i)[^>]*>(.*?)</(em|i)>#isu', '#<u[^>]*>(.*?)</u>#isu');
            $replace = array('<b>$2</b>', '<i>$2</i>', '<u>$1</u>');
            $text = preg_replace($search, $replace, $text);
            //su alcune delle esportazioni di MS Word più recenti, in cui vi è l'istruzione condizionale 'if gte mso 9', etc., 
            //potrebbe trovarsi sottoforma di commenti html e il loro contenuto non viene eliminato dalla funzione strip_tags
            //come accade anche per alcune definizioni di stile Microsoft e classi all'interno dei tag */
            $num_matches = preg_match_all("/\<!--/u", $text, $matches);
            if($num_matches){
                $text = preg_replace('/\<!--(.)*--\>/isu', '', $text);
            }
            $text = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $text);
            $text = preg_replace('/(<[^>]+) class=".*?"/i', '$1', $text);
            $text= str_replace("?","&acute;", $text);
            
            return $text;
            
        }
        
        public function stripTabString($str){
            
            $str= strip_tags($str,'<li><ol><ul><br><p><b><i><strong><h3>');
            $str = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $str);
            $str = preg_replace('/(<[^>]+) class=".*?"/i', '$1', $str);
            $str= str_replace("?","'", $str);
            $str = preg_replace('|style="(.*?)"|i','',$str);
            $str = preg_replace('/(<[^>]*) style=("[^"]+"|\'[^\']+\')([^>]*>)/i', '$1$3', $str);
            $str = preg_replace("/<p[^>]*>[\s|&nbsp;]*<\/p>/", '', $str);
            return $str;
        
        }
        
    }