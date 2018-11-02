<?php 
    
    /**
     * BasePathMiddleware
     */

    namespace App\Middleware;
    
    use Embryo\Http\Response;
    use Psr\Container\ContainerInterface;
    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Server\MiddlewareInterface;
    use Psr\Http\Server\RequestHandlerInterface;
    
    class MaliciousRequestMiddleware implements MiddlewareInterface 
    {   
        /**
         * @var ContainerInterface $container 
         */ 
        private $container; 

        public function __construct(ContainerInterface $container)
        {
            $this->container = $container;
        }

        public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
        {
            $app_url = $this->container['baseUrl'].$this->container['basePath'];
            if($app_url !== $this->container['settings']['app']['url']){
                return new Response(403);
            }
            
            $response = $handler->handle($request);
            return $response;
        }
        
    }