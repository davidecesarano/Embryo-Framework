<?php namespace Helpers;

    /**
     * Image 
     *
     * Esegue delle manipolazioni sulle immagini.
     *
     * @author Davide Cesarano
     */
     
    class Image {
        
        /**
         * @var string $file
         */
        private $file;
        
        /**
         * @var int $width
         */
        private $width;
        
        /**
         * @var int $height
         */
        private $height;
        
        /**
         * @var int $type
         */
        private $type;
         
        /**
         * @var string $format
         */
        private $format;
        
        /**
         * @var mixed $image
         */
        private $image;
        
        /**
         * Qualità dell'immagine salvata/generata
         *
         * @var int $quality
         */
        public $quality = 90;
        
        /**
         * Carica l'immagine
         *
         * @param string $file
         * @return self
         */
        public function __construct($file){
            
            $this->file = SITE_BASE_DIR.'/'.$file;
            $this->load();
        
        }
        
        /**
         * Ottiene le informazioni dell'immagine
         *
         * @return object
         */
        public function load(){
            
            // ottiene informazioni immagine
            $info = getimagesize($this->file);
            $this->width = $info[0];
            $this->height = $info[1];
            $this->type = $info[2];
            $this->format = '';
            
            switch($this->type){
                
                case 1:
                    $this->format = '.gif';
                    $this->image = imagecreatefromgif($this->file);
                break;
                
                case 2:
                    $this->format = '.jpeg';
                    $this->image = imagecreatefromjpeg($this->file);
                break;
                
                case 3:
                    $this->format = '.png';
                    $this->image = imagecreatefrompng($this->file);
                break;
                
            }
            
            imagesavealpha($this->image, true);
            imagealphablending($this->image, true);
            
            return $this;
            
        }
        
        /**
         * Crea una miniatura dell'immagine eseguendo un crop
         * al centro della stessa.
         *
         * @param int $max_width
         * @param int $max_height
         * @return object
         */
        public function thumbnail($max_width, $max_height){
            
            $new_image = imagecreatetruecolor($max_width, $max_height);
            $new_width = $this->height * $max_width / $max_height;
            $new_height = $this->width * $max_height / $max_width;
            
            if($new_width > $this->width){
                //cut point by height
                $h_point = (($this->height - $new_height) / 2);
                //copy image
                imagealphablending($new_image, false);
                imagesavealpha($new_image, true);
                imagecopyresampled($new_image, $this->image, 0, 0, 0, $h_point, $max_width, $max_height, $this->width, $new_height);
            }else{
                //cut point by width
                $w_point = (($this->width - $new_width) / 2);
                imagealphablending($new_image, false);
                imagesavealpha($new_image, true);
                imagecopyresampled($new_image, $this->image, 0, 0, $w_point, 0, $max_width, $max_height, $new_width, $this->height);
            }
    
            // aggiorna informazioni
            // della nuova immagine
            $this->width = $max_width;
            $this->height = $max_height;
            $this->image = $new_image;
            return $this;
        
        }
        
        /**
         * Ridimensiona proporzionalmente in base
         * all'altezza.
         *
         * @param int $height
         * @return object
         */
        public function fitToHeight($height) {
            
            $aspect_ratio = $this->height / $this->width;
            $width = $height / $aspect_ratio;
            return $this->resize($width, $height);
        
        }
        
        /**
         * Ridimensiona proporzionalmente in base
         * alla larghezza.
         *
         * @param int $width
         * @return object
         */
        public function fitToWidth($width) {
            
            $aspect_ratio = $this->height / $this->width;
            $height = $width * $aspect_ratio;
            return $this->resize($width, $height);
            
        }

        /**
         * Ridimensiona immagine
         *
         * @param int $width
         * @param int $height
         * @return object
         */
        public function resize($width, $height){
            
            $image_resized = imagecreatetruecolor($width, $height);
            imagecopyresampled($image_resized, $this->image, 0, 0, 0, 0, $width, $height, $this->width, $this->height);
            $this->image = $image_resized;
            return $this;
            
        }
        
        /**
         * Salva immagine
         *
         * @param string $folder
         * @param string|null $new_filename
         * @return boolean
         */
        public function save($folder, $new_filename = null){
            
            switch($this->type){
                
                case 1:
                    imagegif($this->image, $folder.'/'.$new_filename);
                break;
                case 2:
                    imageinterlace($this->image, true);
                    imagejpeg($this->image, $folder.'/'.$new_filename, round($this->quality));
                break;
                case 3:
                    imagepng($this->image, $folder.'/'.$new_filename, round(9 * $this->quality / 100));
                break;
                
            }
            return true;
            
        }
        
        /**
         * Output immagine senza salvataggio
         *
         * @param string $format
         */
        public function output($format) {
            
            $mimetype = '';
            
            // determina mimetype
            switch (strtolower($format)) {
                case 'gif':
                    $mimetype = 'image/gif';
                break;
                case 'jpeg':
                case 'jpg':
                    imageinterlace($this->image, true);
                    $mimetype = 'image/jpeg';
                break;
                case 'png':
                    $mimetype = 'image/png';
                break;
            }
            
            // genera immagine
            header('Content-Type: '.$mimetype);
            switch ($mimetype) {
                case 'image/gif':
                    imagegif($this->image);
                break;
                case 'image/jpeg':
                    imagejpeg($this->image, null, round($this->quality));
                break;
                case 'image/png':
                    imagepng($this->image, null, round(9 * $this->quality / 100));
                break;
            }
        
        }
        
    }