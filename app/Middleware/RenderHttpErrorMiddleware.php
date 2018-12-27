<?php 

    /**
     * RenderHttpErrorMiddleware
     */

    namespace App\Middleware;
    
    use Embryo\View\View;
    use Embryo\Http\Factory\ResponseFactory;
    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Message\ResponseInterface;
    use Psr\Http\Server\MiddlewareInterface;
    use Psr\Http\Server\RequestHandlerInterface;

    class RenderHttpErrorMiddleware implements MiddlewareInterface 
    {   
        /**
         * @var View $view
         */
        private $view;

        /**
         * Set view for rendering.
         *
         * @param View $view
         * @return self
         */
        public function setView(View $view): self
        {
            $this->view = $view;
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
            $response   = $handler->handle($request);
            $statusCode = $response->getStatusCode();

            if ($statusCode === 404 || $statusCode === 405) {
                return $this->view->render(
                    (new ResponseFactory)->createResponse($statusCode), 
                    'errors',
                    ['title' => 'Ops! '.$response->getReasonPhrase()]
                );
            }
            return $response;
        }   
    }