<?php 

    /**
     * SecureHeadersMiddleware
     */

    namespace App\Middleware;
        
    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Server\MiddlewareInterface;
    use Psr\Http\Server\RequestHandlerInterface;

    class SecureHeadersMiddleware implements MiddlewareInterface 
    {   
        /**
         * @var string FRAME
         */ 
        private const FRAME = 'X-Frame-Options';
        
        /**
         * @var string XSS
         */ 
        private const XSS = 'X-XSS-Protection';

        /**
         * @var string CONTENT
         */ 
        private const CONTENT = 'X-Content-Type-Options';

        /**
         * Process a server request and return a response.
         *
         * @param ServerRequestInterface $request
         * @param RequestHandlerInterface $handler
         * @return ResponseInterface
         */
        public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
        {
            $response = $handler->handle($request);
            $response = $response->withHeader(self::FRAME, 'sameorigin');
            $response = $response->withHeader(self::XSS, '1; mode=block');
            $response = $response->withHeader(self::CONTENT, 'nosniff');
            return $response;
        }   
    }