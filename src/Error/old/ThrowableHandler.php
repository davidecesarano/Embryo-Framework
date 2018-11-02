<?php 
    
    /**
     * ThrowableHandler
     */
    
    namespace Embryo\Error;
    
    use Embryo\Error\AbstractErrorHandler;
    use Embryo\Http\Factory\ResponseFactory;
    use Throwable;
    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Message\ResponseInterface;
    
    class ThrowableHandler extends AbstractErrorHandler
    {    
        public function __invoke(ServerRequestInterface $request, ResponseInterface $response, Throwable $t)
        {
            $output = $this->htmlError($t);
            $body = $response->getBody();
            $body = $body->write($output);
            return $response->withStatus(500);
        }

        protected function htmlError(Throwable $t)
        {
            $title = 'Oops! An error has been occured';
            $html = '<p>The application could not run because of the following error:</p>';
            $html .= '<h2>Details</h2>';
            $html .= $this->renderHtmlError($t);
            /*while ($exception = $exception->getPrevious()) {
                $html .= '<h2>Previous exception</h2>';
                $html .= $this->renderHtmlExceptionOrError($exception);
            }*/
            $output = sprintf(
                "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'>" .
                "<title>%s</title><style>body{margin:0;padding:30px;font:12px/1.5 Helvetica,Arial,Verdana," .
                "sans-serif;}h1{margin:0;font-size:48px;font-weight:normal;line-height:48px;}strong{" .
                "display:inline-block;width:65px;}</style></head><body><h1>%s</h1>%s</body></html>",
                $title,
                $title,
                $html
            );
            return $output;
        }

        protected function renderHtmlError($t)
        {
            $html = sprintf('<div><strong>Type:</strong> %s</div>', get_class($t));
            if (($code = $t->getCode())) {
                $html .= sprintf('<div><strong>Code:</strong> %s</div>', $code);
            }
            if (($message = $t->getMessage())) {
                $html .= sprintf('<div><strong>Message:</strong> %s</div>', htmlentities($message));
            }
            if (($file = $t->getFile())) {
                $html .= sprintf('<div><strong>File:</strong> %s</div>', $file);
            }
            if (($line = $t->getLine())) {
                $html .= sprintf('<div><strong>Line:</strong> %s</div>', $line);
            }
            if (($trace = $t->getTraceAsString())) {
                $html .= '<h2>Trace</h2>';
                $html .= sprintf('<pre>%s</pre>', htmlentities($trace));
            }
            return $html;
        }
        
    }