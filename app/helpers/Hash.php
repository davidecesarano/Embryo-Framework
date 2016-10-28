<?php namespace Helpers;

	/**
	 * Hash
	 *
	 * Esegue il criptaggio di stringhe attraverso 
	 * algoritmi e token.
     *
	 * @author Davide Cesarano
	 */
	
	class Hash{
		
		/**
		 * Crea una stringa criptata
		 *
		 * @param string $algo (ex. = 'md5')
		 * @param string $data (ex. = 'password')
		 * @param string $token (ex. = TOKEN_PASSWORD)
		 * @return string
		 */
		static function create($algo, $data, $token){
			
			$ctx = hash_init($algo, HASH_HMAC, $token);
			hash_update($ctx, $data);
			return hash_final($ctx);
			
		}
		
	}