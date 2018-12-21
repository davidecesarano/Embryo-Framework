<?php 

    namespace App\Helpers;
    
    use \DirectoryIterator;
    
    class Translate 
    {
        private $default = 'en';
        private $messages = [];
        private $current = [];

        public function __construct(string $languagePath, string $default = 'en')
        {
            $this->languagePath = rtrim($languagePath, '/').'/';
            $this->default      = $default;
        }

        public function setMessages(): self
        {
            $messages = [];
            $dirs = new DirectoryIterator($this->languagePath);
            foreach ($dirs as $dir) {
                if ($dir->isDir()) {
                    $langDir = new DirectoryIterator($dir->getPathname());
                    foreach ($langDir as $fileinfo) {
                        if ($fileinfo->getExtension() === 'php') {
                            $messages[$dir->getFilename()] = require $fileinfo->getPathname();
                        }
                    }  
                }
            }
            $this->messages = $messages;
            return $this;
        }

        public function getMessages(string $lang = 'en'): self
        {
            if (isset($this->messages[$lang])) {
                $this->current = $this->messages[$lang];
            } else {
                $this->current = $this->messages[$this->default];
            }
            return $this;
        }

        public function get(string $key, array $context = []): string
        {
            $replace = [];
            foreach ($context as $key => $val) {
                if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                    $replace['{' . $key . '}'] = $val;
                }
            }

            if (isset($this->current[$key])) {
                return strtr($this->current[$key], $replace);
            }
            return '';
        }
    }