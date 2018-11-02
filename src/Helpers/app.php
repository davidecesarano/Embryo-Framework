<?php 

    use Embryo\Container\Container;

    function container($key)
    {
        $container = Container::getInstance();
        return $container[$key];
    }

    function site_url()
    {
        
    }