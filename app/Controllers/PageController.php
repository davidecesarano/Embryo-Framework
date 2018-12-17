<?php 
    
    namespace App\Controllers;
    
    use Embryo\Application\Controller;
    
    class PageController extends Controller
    {        
        public function index($name)
        {
            $view = $this->get('view');
            return $view->render($this->response(), 'home', ['title' => 'Hello '.$name]);
        }
    }