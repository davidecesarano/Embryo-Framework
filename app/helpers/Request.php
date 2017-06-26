<?php namespace Helpers;

	/** 
	 * Request 
	 *
	 * @author Davide Cesarano
	 */
	
	class Request {
		
		/**
		 * @var array $dataArray 
		 */
		public $dataArray = array();
		
		/**
		 * Verifica se la richiesta è di tipo $_POST
		 *
		 * @return boolean
		 */
		public static function isPost(){
			if($_SERVER["REQUEST_METHOD"] === "POST") return true;
		}
		
		/**
		 * Verifica se la richiesta è di tipo $_GET
		 *
		 * @return boolean
		 */
		public static function isGet(){
			if($_SERVER["REQUEST_METHOD"] === "GET") return true;
		}
		
		/**
		 * Verifica se la richiesta è di tipo Ajax
		 *
		 * @return boolean
		 */
		public static function isAjax(){
        
			if(!empty($_SERVER['HTTP_X_REQUESTED_WITH'])){
				if(strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') return true;
			}
        
		}
		
		/**
		 * Ottiene metodo richiesta
		 *
		 * @return string
		 */
		public static function getMethod(){
			
			$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
			
			if(isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])){
				$method = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
			}elseif(isset($_REQUEST['_method'])){
				$method = $_REQUEST['_method'];
			}
			return strtoupper($method);

		}
		
		/**
		 * Ottiene valore $_POST
		 *
		 * @param string $key
		 * @return mixed
		 */
		public static function post($key){
			return array_key_exists($key, $_POST) ? $_POST[$key] : false;
		}
		
		/**
		 * Ottiene valore $_FILE
		 *
		 * @param string $key
		 * @return mixed
		 */
		public static function file($key){
			return array_key_exists($key, $_FILES) ? $_FILES[$key] : null;
		}
		
		/**
		 * Ottiene valore $_GET
		 *
		 * @param string $key
		 * @return mixed
		 */
		public static function get($key){
			return array_key_exists($key, $_GET) ? $_GET[$key] : null;
		}
		
		/**
		 * Genera array dalla richiesta
		 *
		 * @param array $request_array
		 * @return this
		 */
		public static function data($request_array){
			
			$request = new Request;
			return $request->create($request_array);
			
		}
		
		/**
		 * Crea array della richiesta
		 *
		 * @param array $request_array 
		 * @return this
		 */
		public function create($request_array){
			
			$this->dataArray = $request_array;
			return $this;
			
		}
		
		/**
		 * Elimina campo dall'array della richiesta
		 *
		 * @param mixed $key 
		 * @return array
		 */
		public function delete($key){
			
			if(!is_array($key)){
				unset($this->dataArray[$key]);
			}else{
				foreach($key as $value){
					unset($this->dataArray[$value]);
				}
			}
			return $this->dataArray;
		
		}
		
		/**
		 * Ritorna array
		 *
		 * @return array
		 */
		public function getArray(){
			return $this->dataArray;
		}
		
	}