<?php 

    namespace Embryo\Error\Handlers;

    use Embryo\Http\Factory\ResponseFactory;
    use Exception;
    use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};

    class ExceptionHandler 
    {
        public function handle(ServerRequestInterface $request): ResponseInterface
        {
            $error = $request->getAttribute('errorInfo');
            $response = (new ResponseFactory)->createResponse($error->getCode());
            $response->write($this->html($error));
            return $response->withHeader('Content-Type', 'text/html');
        }

        private function html(Exception $error)
        {
            $title = 'Oops! An error has been occured';
            $html = '<p>The application could not run because of the following error:</p>';
            $html .= '<h2>Details</h2>';
            $html .= $this->render($error);
            while ($error = $error->getPrevious()) {
                $html .= '<h2>Previous exception</h2>';
                $html .= $this->render($error);
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

        private function render($e)
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