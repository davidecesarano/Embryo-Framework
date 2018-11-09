<?php 
    
    namespace App\Controllers;
    
    use Embryo\Application\Controller;
    
    class Page extends Controller
    {        
        public function index($request, $response)
        {
            $a = 0;
            if ($a == 1){
                echo 2;
            } else {
                throw new \Embryo\Error\Exceptions\HttpErrorException('', 500);
            }
        }

        public function param($request, $response, $param = null)
        {
            //print_r()
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

    
