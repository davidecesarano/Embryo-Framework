<?php 

    namespace Embryo\Error\Handlers;

    use Embryo\Http\Factory\ResponseFactory;
    use Exception;
    use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};

    class HttpErrorHandler 
    {   
        /**
         * @var array
         */
        private $types = [
            'plain' => [
                'text/plain',
                'text/css',
                'text/javascript',
            ],
            'jpeg' => [
                'image/jpeg',
            ],
            'gif' => [
                'image/gif',
            ],
            'png' => [
                'image/png',
            ],
            'svg' => [
                'image/svg+xml',
            ],
            'json' => [
                'application/json',
            ],
            'xml' => [
                'text/xml',
            ]
        ];

        public function handle(ServerRequestInterface $request): ResponseInterface
        {
            $error    = $request->getAttribute('errorInfo');
            $accept   = $request->getHeaderLine('Accept');
            $response = (new ResponseFactory)->createResponse($error->getCode());

            foreach ($this->types as $method => $types) {
                foreach ($types as $type) {
                    if (stripos($accept, $type) !== false) {
                        $response->getBody()->write(call_user_func(__CLASS__.'::'.$method, $error));
                        return $response->withHeader('Content-Type', $type);
                    }
                }
            }
            $response->write($this->html($error));
            return $response->withHeader('Content-Type', 'text/html');
        }

        private function html(Exception $error)
        {
            $traces = '<ol>';
            foreach ($error->getTrace() as $trace) {
                $traces .= '<li>'.$trace['file'].' ('.$trace['line'].') '.$trace['function'].'</li>';
            }
            $traces .= '</ol>';

            $output = sprintf(
                "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'>" .
                "<title>%s</title><link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css'". 
                "integrity='sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO' crossorigin='anonymous'>".
                "</head><body><h1>%s</h1><div class='highlight'>%s</div></body></html>",
                $error->getMessage(),
                $error->getMessage(),
                $error->getFile()
            );
            return $output;
        }
    }