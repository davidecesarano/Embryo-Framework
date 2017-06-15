<?php namespace Helpers;
    
    /**
     * Directory 
     *
     * @author Davide Cesarano 
     */
    class Directory {
        
        /**
         * Elenco cartelle e file 
         *
         * @param string $directory 
         * @return array 
         */
        public static function getAll($directory){
            
            // cartella da scansionare
			$dir = SITE_BASE_DIR.'/public/'.$directory.'/';
			
			// scansione cartella
			$folders = scandir($dir);
			
			// costruzione array
			$i = 0;
			foreach($folders as $media){
				
				if($media !== "." && $media !== ".htaccess"){
					
					if(is_dir($dir.$media)){
						
                        // elimina torna indietro per uploads
                        if($directory == 'uploads' && $media == '..'){
                            continue;
                        }
                        
                        // cartella
                        $data[$i]['id'] = $i;
                        $data[$i]['name'] = $media;
                        $data[$i]['url'] = ($media == '..') ? preg_replace("/\/([a-zA-Z0-9-_]+)\/..$/", "", $directory.'/'.$media) : $directory.'/'.$media;
                        $data[$i]['type'] = '';
                        $data[$i]['size'] = number_format(self::getSize($dir.$media) / 1024, 0, '', '');
                        $data[$i]['icon'] = 'fa-folder-o';
                        $data[$i]['datetime'] = date("Y-m-d H:i:s", filemtime($dir.$media));
					
					}else{

						// file
						$data[$i]['id'] = $i;
						$data[$i]['name'] = $media;
						$data[$i]['url'] = $directory.'/'.$media;
						$data[$i]['type'] = pathinfo($media, PATHINFO_EXTENSION);
						$data[$i]['icon'] = self::getIcon(pathinfo($media, PATHINFO_EXTENSION));
						$data[$i]['size'] = number_format(filesize($dir.$media) / 1024, 0, '', '');
						$data[$i]['datetime'] = date("Y-m-d H:i:s", filemtime($dir.$media));	
					
					}
					
					$i++;
				
				}
				
			}
			
			// esito
			if(!empty($data)) return $data;
            
        }
        
        /**
         * Elenco delle cartelle (e sotto cartelle)
         *
         * @param string $dir 
         * @param true|false $single
         * @return array 
         */
        public static function getFolders($dir, $single = false){
            
            $dh = scandir(SITE_BASE_DIR.'/'.$dir);
           
            $return = array();
            foreach($dh as $folder){
                
                if($folder != '.' && $folder != '..') {
                    
                    if(!$single){
                        
                        if (is_dir($dir . '/' . $folder)) {
                            $return[$folder] = self::listFolders($dir . '/' . $folder);
                        } else {
                            $return[] = $folder;
                        }
                        
                    }else{
                        $return[] = $folder;
                    }
                    
                }
            
            }
            return $return;
        
        }
        
        /**
         * Ottiene la grandezza complessiva 
         * della cartella (comprese sotto cartelle e file)
         *
         * @param string $folder
         * @return float 
         */
        public static function getSize($folder){
			
			$count_size = 0;
			$count = 0;
			
			$dir_array = scandir($folder.'/');
			
			foreach($dir_array as $key => $filename){
				
				if($filename != ".." && $filename != "."){
					
					if(is_dir($folder."/".$filename)){
						
						$new_foldersize = self::getSize($folder."/".$filename);
						$count_size = $count_size+$new_foldersize;
					
					}else if(is_file($folder."/".$filename)){
					  
						$count_size = $count_size + filesize($folder."/".$filename);
						$count++;
					
					}
			    }
			
			}
			return $count_size;
		
		}
        
        /**
         * Otiene l'icona del file 
         *
         * @param string $type 
         * @return string 
         */
        public static function getIcon($type){
            
            switch($type){
				case 'gif':
				case 'jpeg':
				case 'png':
				case 'jpg':
					return 'fa-file-image-o';
					break;
				case 'pdf':
					return 'fa-file-pdf-o';
					break;
				case 'zip':
				case 'rar':
					return 'fa-file-archive-o';
					break;
				case 'mp3':
				case 'wav':
					return 'fa-file-audio-o';
					break;
				case 'mp4':
				case 'avi':
					return 'fa-file-video-o';
					break;
				default:
					return 'fa-file-o';
					break;
				
			}
            
        }
        
        /**
		 * Elimina
		 *
		 * @param string $element
		 */
		public static function remove($directory){
			
			// cartella da scansionare
			$dir = SITE_BASE_DIR.'/public/'.$directory;
			
			// cartella
			if(is_dir($dir) === true){
				
				$files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir), \RecursiveIteratorIterator::CHILD_FIRST);

				// elimina cartella con file all'interno
				foreach($files as $file){
					
					if(in_array($file->getBasename(), array('.', '..')) !== true){
						
						if($file->isDir() === true){
							
							rmdir($file->getPathName());
						
						}else if(($file->isFile() === true) || ($file->isLink() === true)){
							
							unlink($file->getPathname());
						
						}
					
					}
				
				}
				
				// elimina
				return (rmdir($dir)) ? true : false;
			
			}
		
		}
        
        /**
		 * Breadcrumbs
		 * @todo da verificare
		 * @param array $url 
		 * @return array $breadcrumbs
		 */
		public static function breadcrumbs($url){
			
			$last = count($url);
			$tmp = '';
			
			foreach($url as $key => $value){

				if($key == 0){				
					
					// uploads, prima cartella
					$breadcrumbs[$value] = $value;
				
				}elseif($key > 0 && $key < $last){

					// cartelle intermedie
					for($i = 0; $i <= $key; $i++){
						$tmp .= $url[$i].'/';
					}
					$breadcrumbs[$value] = substr($tmp, 0, strlen($tmp)-1);
					$tmp = '';
				
				}else{
					
					// ultima cartella
					$breadcrumbs[$value] = implode($url, '/');
					
				}
				
			}
			return $breadcrumbs;
			
		}
        
        /**
         * Verifica se la directory è vuota
         *
         * @param string $directory 
         * @param array $exceptions 
         * @return bool 
         */
        public static function is_empty($directory, $exceptions = ['.', '..']){
        
            if(!is_dir($directory)) return false;
            
            foreach(scandir($directory) as $file){
                if(!in_array($file, $exceptions)) return false;
            }
            return true;
        
        }
        
    }