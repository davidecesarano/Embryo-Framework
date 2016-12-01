<?php namespace Controllers;
	
	/**
	 * Page
	 *
	 * @author Davide Cesarano
	 */
	
	use Core\Controller;
	
	class Page extends Controller{
        
        /**
		 * Home Page
		 */
		public function index(){

			// variabili seo
			$this->view->title = title();
			$this->view->keywords = keywords();
			$this->view->description = description();
			
			// template
			$this->loadVieW('home');
			
		}
        
        /**
         * Page 
         */
		public function example(){

			// variabili seo
			$this->view->title = 'Example - '.title();
			$this->view->keywords = keywords();
			$this->view->description = description();
			
			// template
			$this->loadVieW('pages/single');
			
		}
        
	}