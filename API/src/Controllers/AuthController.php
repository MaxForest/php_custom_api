<?php
/**
 * 
 */
class AuthController
{
	public function Get($params) {
		if(!isset($params['grant_type']) || !isset($params['username']) || !isset($params['password']))
		{
			throw new Exception("Error : Missing credentials", 1);
		}

		if($params['grant_type'] != 'password')
		{
			throw new Exception("Error : Invalid grant_type", 1);
		}
		
		// Afin de generer un bearer token, on verifie avant si un user avec les informations donnees existe.
		$mysql = new MySqlConnect(CONST_DB_HOST, CONST_DB_USERNAME, CONST_DB_PASSWORD, CONST_DB);

		$stmt = $mysql->conn->prepare("SELECT * from usagers where username = ? and password = ?");
		$stmt->bind_param("ss", $username, $password);

		$username = $params['username'];
		$password = $params['password'];

		$stmt->execute();

		$res = $stmt->get_result();
		$row = $res->fetch_assoc();

		if($res->num_rows > 0)
		{
			// Generation d'un cle encrypte via openssl - nous allons la decrypter dans API_extension
			$plaintext = $params['username'].".".CONST_SECRETKEY;
			$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher=CONST_CIPHER));
			return openssl_encrypt($plaintext, $cipher, CONST_KEY, $options=0, $iv);
		}
		else
		{
			throw new Exception("Error Processing Request", 1);
		}
	}
}