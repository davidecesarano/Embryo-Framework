<?php 

    namespace Embryo\Error\Handlers;

    class ErrorShutdownHandler 
    {
        public function __invoke()
        {
            $error = error_get_last();

            if (empty($error)) {
                return false;
            }
            
            $title = 'Oops! An error has been occured';
            $html = '<p>The application could not run because of the following error:</p>';
            $html .= '<h2>Details</h2>';
            $html .= $this->render($error);
            $output = sprintf(
                "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'>" .
                "<title>%s</title><style>body{margin:0;padding:30px;font:12px/1.5 Helvetica,Arial,Verdana," .
                "sans-serif;}h1{margin:0;font-size:48px;font-weight:normal;line-height:48px;}strong{" .
                "display:inline-block;width:65px;}</style></head><body><h1>%s</h1>%s</body></html>",
                $title,
                $title,
                $html
            );
            echo $output;
        }

        private function render($e)
        {
            $html .= sprintf('<div><strong>Type:</strong> %s</div>', $e['type']);
            $html .= sprintf('<div><strong>Message:</strong> %s</div>', htmlentities($e['message']));
            $html .= sprintf('<div><strong>File:</strong> %s</div>', $e['file']);
            $html .= sprintf('<div><strong>Line:</strong> %s</div>', $e['line']);
            return $html;
        }
    }