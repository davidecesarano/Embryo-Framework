<?php namespace Helpers;
	
	/**
	 * Asset 
	 *
	 * Si occupa della gestione degli assets
	 * (js, css, immagini, fonts) dell'applicazione
	 *
	 * @author Davide Cesarano
	 */
    
    use \Exception;
    use Core\Config;
	 
	class Asset {
		
		/**
		 * Nuovo asset da componente in 'vendor/assets'
		 *
		 * @param string $name
		 * @return this
         * @throws exception
		 */
		public static function component($name){

            $components = Config::get('alias', 'assets');
            
            if(array_key_exists($name, $components) && file_exists(SITE_BASE_DIR.'/'.FOLDER_ASSETS.'/'.$components[$name])){
                
                $asset = new Asset;
                return $asset->loadComponent($components[$name]);
                
            }else{
                throw new Exception("Il componente <strong>$name</strong> non esiste!");
            }
            
		}
		
		/**
		 * Nuovo asset da componente della dashboard
		 *
		 * @param string|null $folder
		 * @return this
		 */
		public static function dashboard($folder = null){
			
			$asset = new Asset;
			return $asset->loadFromDashboard($folder);
			
		}
		
		/**
		 * Nuovo asset da componente del tema
		 *
         * @param string|null $folder
		 * @return this
		 */
		public static function theme($folder = null){
			
			$asset = new Asset;
			return $asset->loadFromTheme($folder);
			
		}
		
		/**
		 * Carica componente in 'vendor/assets'
		 * e scansiona le distribuzioni
		 *
		 * @param string $folder
		 * @return this
		 */
		public function loadComponent($folder){
			
			$this->asset['component'] = site_url().'/'.FOLDER_ASSETS.'/'.$folder;
			return $this;
		
		}
		
		/**
		 * Carica componente della dashboard
		 *
		 * @param string|null $folder
		 * @return this
		 */
		public function loadFromDashboard($folder = null){
			
			$this->asset['dashboard'] = ($folder) ? site_template_admin().'/assets/vendor/'.$folder : site_template_admin().'/assets';
			return $this;
			
		}
		
		/**
		 * Carica componente del tema
		 *
         * @param string|null $folder
		 * @return this
		 */
		public function loadFromTheme($folder = null){
			
			$this->asset['theme'] = ($folder) ? site_template_public().'/assets/vendor/'.$folder : site_template_public().'/assets';
			return $this;
			
		}
        
        /**
         * Carica cartella
         *
         * @param string $path 
         * @return this 
         */
        public function folder($path){
            
            $this->asset['folder'] = $path;
            return $this;
            
        }
         
		
		/**
		 * Seleziona file css
		 *
		 * @param string $file 
		 * @return this 
		 */
		public function css($file){
			
			$this->asset['css'] = 'css/'.$file.'.css';
			return $this->getSingle();
		
		}
		
		/**
		 * Seleziona file js
		 *
		 * @param string $file 
		 * @return this 
		 */
		public function js($file){
			
			$this->asset['js'] = 'js/'.$file.'.js';
			return $this->getSingle();
		
		}
        
        /**
		 * Seleziona file font
		 *
		 * @param string $file 
		 * @return this 
		 */
		public function font($file){
			
			$this->asset['font'] = 'fonts/'.$file;
			return $this->getSingle();
		
		}
        
        /**
		 * Seleziona file immagine da
         * cartella images
		 *
		 * @param string $file 
		 * @return this 
		 */
		public function image($file){
			
			$this->asset['image'] = 'images/'.$file;
			return $this->getSingle();
		
		}
        
        /**
		 * Seleziona file immagine da
         * cartella img
		 *
		 * @param string $file 
		 * @return this 
		 */
		public function img($file){
			
			$this->asset['img'] = 'img/'.$file;
			return $this->getSingle();
		
		}
		
		/**
		 * Seleziona file generico
		 *
		 * @param string $path 
		 * @return this 
		 */
		public function file($path){
			
			$this->asset['file'] = $path;
			return $this->getSingle();
		
		}
		
		/**
		 * Stampa percorso completo
		 *
		 * @return string
		 */
		public function getSingle(){
			return implode('/', $this->asset);
		}
	
	}