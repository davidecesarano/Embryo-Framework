<?php namespace Core;

	/**
	 * View
	 * 
	 * Una vista non è altro che un template che visualizza i dati inviati dal controller 
	 * successivamente all'eventuale elaborazione dei modelli.
	 *
	 * @author Davide Cesarano
	 */
	
	use \Exception;
	
	class View{
		
		/**
		 * @var array $vars
		 */
		protected $vars = array();
		
		/**
		 * Scrive il valore di un attributo inaccessibile
		 *
		 * @param mixed $key
		 * @param mixed $value
		 * @return object
		 */
		public function __set($key, $value){
			$this->vars[$key] = $value;
		}
		
		/**
		 * Legge il valore di un attributo inaccessibile
		 *
		 * @param mixed $index
		 * @return object
		 */
		public function __get($index){
			return $this->vars[$index];	
		}
		
		/**
		 * Carica il modello (public o dashboard) ed imposta
		 * le variabili da caricare
		 *
		 * @param string $name Nome del file
		 * @param string $end 'public' o 'dashboard'
		 * @throws exception
		 */
		public function render($name, $end = null){
			
			// imposta variabili (es. $this->view->name diventa $name)
			extract($this->vars);
			
			// include il file del template
			if($end == 'public' || $end == null){
				$template = folder_template_public().'/views/'.$name.'.php';
			}elseif($end == 'dashboard'){
				$template = folder_template_admin().'/views/'.$name.'.php';
			}

			if(file_exists($template)){
				require $template;
			}else{
				throw new Exception("Il file <strong>$name</strong> non esiste!");
			}
			
		}
		
		/**
		 * Include i file del template 'public'
		 *
		 * @param string $name
		 * @throws exception
		 */
		public function template_part($name){
			
			// imposta variabili
			extract($this->vars);
			$template = folder_template_public().'/views/'.$name.'.php';
				
			if(file_exists($template)){
				require $template;
			}else{
				throw new Exception("Il file <strong>$name</strong> non esiste!");
			}
		
		}
		
		/**
		 * Include i file del template 'dashboard'
		 *
		 * @param string $name
		 * @throws exception
		 */
		public function template_dashboard_part($name){
			
			// imposta variabili
			extract($this->vars);
			$template = folder_template_admin().'/views/'.$name.'.php';

			if(file_exists($template)){
				require $template;
			}else{
				throw new Exception("Il file <strong>$name</strong> non esiste!");
			}
		
		}
	
	}