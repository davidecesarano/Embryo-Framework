<?php
    
    function loader($class){
        
        $file = $class.'.php';
        if(file_exists($file)){
            require $file;
        }
    }
    spl_autoload_register('loader');
    
    if(!class_exists('\PHPUnit\Framework\TestCase') && class_exists('\PHPUnit_Framework_TestCase')){
        class_alias('\PHPUnit_Framework_TestCase', '\PHPUnit\Framework\TestCase');
    }