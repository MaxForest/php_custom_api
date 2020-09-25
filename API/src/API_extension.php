<?php
	class API_extension {

		private $function_map;

		public function __construct() {
			$this->loadFunctionMap();
		}

		public function execCommand($call, $parametres = '') 
		{
			$command = $this->getCommand($call);

			$class = $command['data']['class'];
			$method = $command['data']['method'];

			$cObjectClass = new $class();
			$command = $cObjectClass->$method($parametres);

			return $command;
		}

		private function getCommand($call) {
			if (isset($this->function_map[$call])) {
				$data['class'] = $this->function_map[$call]['class'];
				$data['method'] = $this->function_map[$call]['method'];
				$command['data'] = $data;
			} 
			else {
				throw new Exception("Method not supported", 1);
			}

			return $command;
		}

		private function loadFunctionMap() {
			$this->function_map = [
				'Get' => ['class' => 'ServeurController', 'method' => 'Get'],
				'Post' => ['class' => 'ServeurController', 'method' => 'Post'],
				'Put' => ['class' => 'ServeurController', 'method' => 'Put'],
				'Delete' => ['class' => 'ServeurController', 'method' => 'Delete'],
				'Token' => ['class' => 'AuthController', 'method' => 'Get']
			];
		}

		public function decryptToken($token)
		{
			try
			{
				$hmac = hash_hmac('sha256', $token, CONST_KEY, $as_binary=true);
				$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher=CONST_CIPHER));
				$ciphertext = base64_encode( $iv.$hmac.$token);

				$c = base64_decode($ciphertext);
				$ivlen = openssl_cipher_iv_length($cipher=CONST_CIPHER);

				$iv = substr($c, 0, $ivlen);
				$hmac = substr($c, $ivlen, $sha2len=32);
				$token = substr($c, $ivlen+$sha2len);

				$original_token = openssl_decrypt($token, $cipher, CONST_KEY, $options=0, $iv);

				// Avoir eu plus de temps, j'aurai approfondie sur le sujet du cipher
				// Pour l'exercice, si original_token retourne false, c'est que le token n'est pas bon
				// L'idee est de comparer le secret key dans le token et celui dans CONST_SECRETKEY
				return $original_token != false;
			}
			catch(Exception $e)
			{
				return false;
			}
		}
	}
?>