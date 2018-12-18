<?php 

    /**
     * Mail
     * 
     * Helper class for PHPMailer.
     */

    namespace App\Helpers;

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    class Mail 
    {
        /**
         * Set account.
         *
         * @param array $account
         */
        public function __construct(array $account)
        {
            $this->account = $account;
        }

        /**
         * Set PHPMailer object.
         *
         * @param string $name
         * @throws RuntimeException
         * @return PHPMailer
         */
        public function account(string $name = 'local'): PHPMailer
        {
            if (!isset($this->account[$name])) {
                throw new \RuntimeException("Account $name doesn't exists.");
            }

            $exceptions = $this->account[$name]['exceptions'];
            $debug      = $this->account[$name]['debug'];
            $host       = $this->account[$name]['host'];
            $port       = $this->account[$name]['port'];
            $username   = $this->account[$name]['username'];
            $password   = $this->account[$name]['password'];
            $html       = $this->account[$name]['html'];
            $smtpsecure = $this->account[$name]['smtpsecure'];
            $mail       = new PHPMailer($exceptions);

            $this->CharSet   = 'UTF-8';
            $mail->SMTPDebug = $debug;
            $mail->isSMTP();

            $mail->Host       = $host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $username;
            $mail->Password   = $password;
            $mail->SMTPSecure = $smtpsecure;
            $mail->Port       = $port;
            $mail->isHTML($html); 
            return $mail;
        }
    }