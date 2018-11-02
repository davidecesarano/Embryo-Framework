<?php 

    namespace App\Middleware;
        
    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Server\MiddlewareInterface;
    use Psr\Http\Server\RequestHandlerInterface;

    class SecureHeadersMiddleware implements MiddlewareInterface 
    {    
        const FRAME   = 'X-Frame-Options';
        const XSS     = 'X-XSS-Protection';
        const CONTENT = 'X-Content-Type-Options';

        public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
        {
            $response = $handler->handle($request);
            $response = $response->withHeader(self::FRAME, 'sameorigin');
            $response = $response->withHeader(self::XSS, '1; mode=block');
            $response = $response->withHeader(self::CONTENT, 'nosniff');
            return $response;
        }
        
    }