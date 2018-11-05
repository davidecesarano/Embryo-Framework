<?php 
    
    namespace App\Controllers;
    
    use Embryo\Application\Controller;
    
    class Page extends Controller
    {        
        public function index($request, $response)
        {
            $base = $this->container->get('baseUrl');
            return $response->write('Controller '.$base);
        }

        public function param($request, $response, $param)
        {
            return $response->write('Controller con parametro '.$param);
        }

        public function int($request, $response, $int)
        {
            return $response->write('Controller con parametro intero '.$int);
        }

        public function middleware($request, $response, $int)
        {
            return $response->write('Controller con parametro intero '.$int.' e middleware');
        }

        public function optional($request, $response, $param = 'Test')
        {
            return $response->write('Optional '.$param);
        }
    }

    
