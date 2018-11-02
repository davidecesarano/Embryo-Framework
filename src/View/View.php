<?php 
    
    namespace Embryo\View;
    
    use Psr\Http\Message\ResponseInterface;

    class View 
    {
        /**
         * @var string $templatePath
         */
        private $templatePath;

        public function __construct(string $templatePath)
        {
            $this->templatePath = rtrim($templatePath, '/');
            return $this;
        }
        
        public function render(ResponseInterface $response, string $template, array $data = [])
        {
            try {

                ob_start();
                $this->include($template, $data);
                $output = ob_get_clean();

            } catch(\Throwable $e) {
                ob_end_clean();
                throw $e;
            }
            return $response->write($output);
        }

        public function include(string $template, array $data = [])
        {
            $file = $this->templatePath.'/'.$template.'.php';
            if (!is_file($file)) {
                throw new \RuntimeException("View cannot render $file because the template does not exist");
            }

            extract($data);
            include $file;
        }
    }