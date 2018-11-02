<?php 
    
    /**
     * ExceptionHandler
     */

    namespace Embryo\Error;
    
    use Embryo\Error\AbstractErrorHandler;
    use Embryo\Http\Factory\ResponseFactory;
    use Exception;
    use Psr\Http\Message\ServerRequestInterface;
    use Psr\Http\Message\ResponseInterface;
    
    class ExceptionHandler extends AbstractErrorHandler
    {    
        public function __invoke(ServerRequestInterface $request, ResponseInterface $response, Exception $e)
        {
            $output = $this->htmlError($e);
            $body = $response->getBody();
            $body = $body->write($output);
            
            return $response->withStatus(500);
        }

        protected function htmlError(Exception $e)
        {
            $title = 'Oops! An error has been occured';
            $html = '<p>The application could not run because of the following error:</p>';
            $html .= '<h2>Details</h2>';
            $html .= $this->renderHtmlError($e);
            while ($e = $e->getPrevious()) {
                $html .= '<h2>Previous exception</h2>';
                $html .= $this->renderHtmlError($e);
            }
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

        protected function renderHtmlError($e)
        {
            $html = sprintf('<div><strong>Type:</strong> %s</div>', get_class($e));
            if (($code = $e->getCode())) {
                $html .= sprintf('<div><strong>Code:</strong> %s</div>', $code);
            }
            if (($message = $e->getMessage())) {
                $html .= sprintf('<div><strong>Message:</strong> %s</div>', htmlentities($message));
            }
            if (($file = $e->getFile())) {
                $html .= sprintf('<div><strong>File:</strong> %s</div>', $file);
            }
            if (($line = $e->getLine())) {
                $html .= sprintf('<div><strong>Line:</strong> %s</div>', $line);
            }
            if (($trace = $e->getTraceAsString())) {
                $html .= '<h2>Trace</h2>';
                $html .= sprintf('<pre>%s</pre>', htmlentities($trace));
            }
            return $html;
        }
        
    }