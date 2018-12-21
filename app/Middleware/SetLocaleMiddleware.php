<?php 

    /**
     * SetLocaleMiddleware
     */

    namespace App\Middleware;
        
    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Server\MiddlewareInterface;
    use Psr\Http\Server\RequestHandlerInterface;

    class SetLocaleMiddleware implements MiddlewareInterface 
    {   
        private $lang = 'en';

        public function setLanguage(string $lang)
        {
            $this->lang = $lang;
            return $this;
        }

        /**
         * Process a server request and return a response.
         *
         * @param ServerRequestInterface $request
         * @param RequestHandlerInterface $handler
         * @return ResponseInterface
         */
        public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
        {
            $session = $request->getAttribute('session');
            $query   = $request->getQueryParams();

            if (isset($query['language'])) {
                $session->set('language', $query['language']);
            }

            if (!$session->has('language')) {
                $session->set('language', $this->lang);
            }
            return $handler->handle($request);
        }   
    }