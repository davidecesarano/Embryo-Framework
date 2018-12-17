<?php 

    use Embryo\Application\Facades\Container;

    function debug($data)
    {
        echo '<pre>';
            print_r($data);
        echo '</pre>';
    }

    function site_url($path = null)
    {
        $url = Container::get('baseUrl');
        return (!$path) ? $url : $url.'/'.trim($path, '/');
    }

    function app_path($path = null) 
    {
        $settings = Container::get('settings');
        $app_path = rtrim($settings['app']['root_path'], '/'); 
        return (!$path) ? $app_path : $app_path.'/'.trim($path, '/').'/';
    }

    function template_path($path = null)
    {
        $settings      = Container::get('settings');
        $template_path = $settings['view']['path']; 
        return (!$path) ? $template_path : $template_path.'/'.trim($path, '/');
    }

    function csrf_token()
    {
        $request = Container::get('request');
        $session = $request->getAttribute('session');
        $tokens  = $session->get('csrf_token', []);
        return end($tokens);
    }