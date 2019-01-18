<?php 

    /**
     * BodyParserMiddleware
     */

    namespace App\Middleware;
        
    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Server\MiddlewareInterface;
    use Psr\Http\Server\RequestHandlerInterface;
    use Embryo\Http\Factory\StreamFactory;

    class BodyParserMiddleware implements MiddlewareInterface 
    {   
        /**
         * Process a server request and return a response.
         *
         * @param ServerRequestInterface $request
         * @param RequestHandlerInterface $handler
         * @return ResponseInterface
         */
        public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
        {
            $contentType = $request->getHeaderLine('Content-Type');
            if (count($contentType) > 0 && $contentType === 'application/json') {
                
                $stream = fopen('php://temp', 'w+');
                stream_copy_to_stream(fopen('php://input', 'r'), $stream);
                rewind($stream);
                
                $body    = (new StreamFactory)->createStreamFromResource($stream);
                $params  = json_decode($body->getContents(), true);
                $request = $request->withBody($body)->withParsedBody($params);

            }
            return $handler->handle($request);  
        }   
    }