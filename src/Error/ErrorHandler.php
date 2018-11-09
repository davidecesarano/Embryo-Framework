<?php 

    namespace Embryo\Error;

    use Embryo\Error\Traits\{ErrorFormatTrait, ErrorLogTrait};
    use Embryo\Http\Factory\ResponseFactory;
    use Psr\Http\Message\{ServerRequestInterface, ResponseInterface};
    use Psr\Log\LoggerInterface;

    class ErrorHandler 
    {   
        use ErrorFormatTrait, ErrorLogTrait;

        /**
         * @var array
         */
        private $types = [
            'plain' => ['text/plain', 'text/css', 'text/javascript'],
            'html'  => ['text/html'],
            'json'  => ['application/json'],
            'xml'   => ['text/xml']
        ];

        private $displayDetails = true;
        private $logErrors = false;
        protected $logger;

        public function __construct($displayDetails = true, $logErrors = false)
        {
            $this->displayDetails = $displayDetails;
            $this->logErrors      = $logErrors;
        }

        public function setLogger(LoggerInterface $logger)
        {
            $this->logger = $logger;
            return $this;
        }

        public function process(ServerRequestInterface $request, \Throwable $exception): ResponseInterface
        {
            $code     = ($exception->getCode() === 0) ? 500 : $exception->getCode();
            $response = (new ResponseFactory)->createResponse($code);
            $accept   = $request->getHeaderLine('Accept');

            if ($this->logErrors) {
                $this->log($request, $response, $exception);
            }

            foreach ($this->types as $method => $types) {
                foreach ($types as $type) {
                    if (stripos($accept, $type) !== false) {
                        $output = $this->{$method}($exception, $response->getReasonPhrase());
                        $response = $response->write($output);
                        return $response->withHeader('Content-Type', $type);
                    }
                }
            }
        }
    }