<?php 

    /**
     * ServerRequestService
     */

    namespace Embryo\Services;

    use Embryo\Container\ServiceProvider;
    use Embryo\Http\Factory\{ServerRequestFactory, StreamFactory};
    
    class ServerRequestService extends ServiceProvider
    {
        /**
         * Registers service provider.
         *
         * @return void
         */
        public function register()
        {
            $this->container->set('request', function(){
                
                $request     = (new ServerRequestFactory)->createServerRequestFromServer();
                $contentType = $request->getHeaderLine('Content-Type');

                if ($contentType !== '' && strpos($contentType, 'application/json') !== false) {

                    $stream = fopen('php://temp', 'w+');
                    stream_copy_to_stream(fopen('php://input', 'r'), $stream);
                    rewind($stream);
                    
                    $body    = (new StreamFactory)->createStreamFromResource($stream);
                    $params  = json_decode($body->getContents(), true);
                    $request = $request->withBody($body)->withParsedBody($params);

                }
                return $request;

            });
        }
    }