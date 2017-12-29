<?php namespace Helpers;

	/**
	 * Email
	 *
	 * Classe che sfrutta le potenzionalità di PHPMailer
	 * per l'invio di email dall'applicazione.
	 *
	 * @author Davide Cesarano
	 */
	
	use PHPMailer;
	use POP3;
	use SMTP;
    use Core\Config;
	
	class Mail extends PHPMailer {

		/**
		 * Configura PHPMailer
		 *
		 * @return object $mail
		 */		
		public function account($account){

			$this->isSMTP();
            $this->CharSet = "UTF-8";
			$this->SMTPDebug = 0;
			$this->Debugoutput = 'html';
			$this->Host = $account['host'];
			$this->Port = $account['port'];
			$this->SMTPSecure = 'tls';
			$this->SMTPAuth = true;
			$this->Username = $account['username'];
			$this->Password = $account['password'];
			$this->IsHTML(true);
			
		}
		
	}