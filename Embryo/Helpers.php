<?php 

    /**
     * Helpers
     * 
     * Helper PHP function.
     * 
     * @author Davide Cesarano <davide.cesarano@unipegaso.it>
     * @link https://github.com/davidecesarano/embryo-application
     */

    use Embryo\Facades\Container;

    /**
     * Debug for information about 
     * a variable.
     *
     * @param mixed $data
     * @return string
     */
    function debug($data)
    {
        echo '<pre>';
            print_r($data);
        echo '</pre>';
    }

    /**
     * Dumps information about a variable.
     *
     * @param mixed $data
     * @return string
     */
    function dump($data)
    {
        echo '<pre>';
            var_dump($data);
        echo '</pre>';
    }

    /**
     * Generate a fully qualified URL 
     * from to app url.
     *
     * @param string|null $path
     * @return string
     */
    function site_url(string $path = null)
    {
        $url = Container::get('baseUrl');
        return (!$path) ? $url : $url.'/'.trim($path, '/');
    }

    /**
     * Generate a fully qualified path
     * from the project root.
     *
     * @param string|null $path
     * @return string
     */
    function app_path(string $path = null) 
    {
        $settings = Container::get('settings');
        $app_path = rtrim($settings['app']['root_path'], '/'); 
        return (!$path) ? $app_path : $app_path.'/'.ltrim($path, '/');
    }

    /**
     * Generate a fully qualified URL
     * from the asset resources.
     * 
     * If versioning is true, append the 
     * filemtime to query param 'v'.
     *
     * @param string $path
     * @param bool $versioning
     * @return string
     */
    function asset(string $path, $versioning = false)
    {
        if ($versioning) {
            $res = filemtime(app_path('public/assets/'.trim($path, '/')));
            return site_url('assets/'.$path.'?v='.$res);
        } else {
            return site_url('assets/'.$path);
        }
    }

    /**
     * Return CSRF token from request session 
     * attribute.
     *
     * @param string $name
     * @return string
     */
    function csrf_token($name = 'csrf_token')
    {
        $request = Container::get('request');
        $session = $request->getAttribute('session');
        $tokens  = $session->get('csrf_token', []);
        return '<input type="hidden" name="'.$name.'" value="'.end($tokens).'">';
    }

    /**
     * Return message translation.
     *
     * @param string $key
     * @param array $context
     * @return string
     */
    function trans(string $key, array $context = [])
    {
        $locale   = Container::get('settings')['app']['locale'];
        $language = Container::get('request')->getAttribute('session')->get('language', $locale);
        $messages = Container::get('translate')->getMessages($language);
        return $messages->get($key, $context);
    }