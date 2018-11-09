<?php 

    namespace Embryo\Error\Traits;

    trait ErrorFormatTrait 
    {
        protected function plain(\Throwable $error, string $phrase)
        {
            return sprintf('%s: %s in %s on line %d',
                get_class($error),
                $error->getMessage(),
                $error->getFile(),
                $error->getLine()
            );
        }

        protected function html(\Throwable $error, string $phrase)
        {
            if ($this->displayDetails) {

                $title = $phrase;

                $details = sprintf(
                    "<table>
                        <tr><td class='pr-3 font-weight-bold'>Type:</td><td>%s</td></tr>
                        <tr><td class='pr-3 font-weight-bold'>Code:</td><td>%d</td></tr>
                        <tr><td class='pr-3 font-weight-bold'>Message:</td><td>%s</td></tr>
                        <tr><td class='pr-3 font-weight-bold'>File:</td><td>%s</td></tr>
                        <tr><td class='pr-3 font-weight-bold'>Line:</td><td>%s</td></tr>
                    </table>",
                    get_class($error),
                    $error->getCode(),
                    $error->getMessage(),
                    $error->getFile(),
                    $error->getLine()
                );
    
                $trace = $error->getTraceAsString();

            } else {

                $title   = 'Ops! Embryo Application Error';
                $message = 'Sorry, something went wrong.';
                $details = '';
                $trace   = '';
                
            }
            
            $output = sprintf(
                "<html>
                    <head>
                        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
                        <title>%s</title>
                        <link 
                            rel='stylesheet' 
                            href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' 
                            integrity='sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO' 
                            crossorigin='anonymous'>
                    </head>
                    <body>
                        <div class='p-4'>
                            <h1 class='display-4 mb-0'>%s</h1>
                            <p>%s</p>
                            <div class='card mt-4'>
                                <div class='card-body'>
                                    <h5 class='card-title font-weight-bold'>Details</h5>
                                    %s
                                </div>
                            </div>
                            <div class='card mt-4'>
                                <div class='card-body'>
                                    <h5 class='card-title font-weight-bold'>Trace</h5>
                                    <pre>%s</pre>
                                </div>
                            </div>
                        </div>
                    </body>
                </html>",
                $title,
                $title,
                $message,
                $details,
                $trace
            );
            return $output;
        }
    }