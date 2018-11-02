<?php 
    
    namespace App\Middleware;
    
    use Embryo\Http\Factory\StreamFactory;
    use Minify_HTML;
    use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
    use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};
    
    class MinifyHtmlMiddleware implements MiddlewareInterface 
    {    
        public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
        {
            $response = $handler->handle($request);
            if (stripos($response->getHeaderLine('Content-Type'), 'text/html') === 0) {
                
                $stream = (new StreamFactory)->createStream();
                $stream->write($this->minify((string) $response->getBody()));
                return $response->withBody($stream);

            }
            return $response;
        }

        private function minify($content)
        {
            return preg_replace('/\s+/', ' ', $content);
        }
        
    }