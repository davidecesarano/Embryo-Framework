<?php 

    namespace App\Middleware;
        
    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Server\MiddlewareInterface;
    use Psr\Http\Server\RequestHandlerInterface;
    use Embryo\Http\Factory\ResponseFactory;

    class ResponseTimeMiddleware implements MiddlewareInterface {
        
        const HEADER = 'X-Response-Time';

        public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
        {
            $server    = $request->getServerParams();
            $startTime = $server['REQUEST_TIME_FLOAT'] ? $server['REQUEST_TIME_FLOAT'] : microtime(true);
            $response  = $handler->handle($request);
            return $response->withHeader(self::HEADER, sprintf('%2.3fms', (microtime(true) - $startTime) * 1000));
        }
        
    }