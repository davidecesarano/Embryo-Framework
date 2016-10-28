<?php namespace Core;
	
	/**
	 * Controller
	 *
	 * Interpreta i dati in input provenienti dall'utente e li rimappa
	 * in comandi che sono inviati al modello ed eventualmente alla 
	 * vista per effettuare le opportune variazioni.
	 *
	 * @author Davide Cesarano
	 */
	
	use \Exception;
	use Core\Config;
	use Core\View;
	
	class Controller{
		
		/**
		 * @var object view 
		 */
		protected $view;
		
		/**
		 * Attiva la vista
		 *
		 * @return object $this->view
		 */
		public function __construct(){
            $this->view = new View;	
        }
		
		/**
		 * Carica il modello
		 *
		 * @param string $name Nome del modello
		 * @return object $this->name_model
		 * @throws exception
		 */
		public function loadModel($name){
            
            // nome e classe
            if(strstr($name, '\\')){ 
                
                $exp = explode('\\', $name);
                $class = 'Models\\'.ucfirst($exp[0]).'\\'.ucfirst($exp[1]);
                $name_model = $exp[1].'_model';
            
            }else{
                
                $class = 'Models\\'.ucfirst($name);
                $name_model = $name.'_model';
           
            }
			
			// carica modello se esiste
			if(class_exists($class)){
				$this->{$name_model} = new $class;
			}else{
				throw new Exception("Il modello $name non esiste!");
			}
			
		}
		
		/**
		 * Carica il template e attiva Widget
		 *
		 * @param string $name Nome del file del template da includere
		 * @param string $end Specifica se si tratta di "Dashboard" o "Public"
		 */
		public function loadView($name, $end = null){
			$this->view->render($name, $end);
		}
		
		/**
		 * Carica pagina Errore 404
         *
         * @param mixed $message
		 */
		public function loadError404($message = null){
			
			// variabili seo
			$this->view->title = 'Pagina non disponibile - '.title();
			$this->view->keywords = '';
			$this->view->description = '';
            
            // messaggio
            $this->view->message = $message;
			
			// template
			$this->loadView('404');
            
            // esci
            exit;
		
		}
	
	}