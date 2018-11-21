<?php 
    
    namespace App\Controllers;
    
    use Embryo\Application\Controller;
    
    class PageController extends Controller
    {        
        public function index()
        {
            $view = $this->get('view');
            return $view->render($response, 'home', ['title' => 'Embryo 2']);
        }
    }