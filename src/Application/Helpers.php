<?php 

    use Embryo\Application\Facades\Container;

    function site_url($path = null)
    {
        $url = Container::get('baseUrl');
        return (!$path) ? $url : $url.'/'.trim($path, '/');
    }

    function template_path($path = null)
    {
        $settings      = Container::get('settings');
        $template_path = $settings['view']['path']; 
        return (!$path) ? $template_path : $template_path.'/'.trim($path, '/');
    }