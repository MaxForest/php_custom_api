<?php
	header('Access-Control-Allow-Origin: *'); 
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
	header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
	header('Content-Type: application/json');

	require_once ('./src/autoloader.php');

	// Dans le meilleur des mondes, j'aurai pris le $_SERVER['REQUEST_METHOD'] comme méthode à aller chercher
	if (in_array($_SERVER['REQUEST_METHOD'], ["GET", "POST", "PUT", "DELETE"])) 
	{
		$parameters = null;
		$method = 'Get';
		
		if (isset($_REQUEST['token']))		{ $token = $_REQUEST['token'];}
		if (isset($_REQUEST['method']))		{ $method = $_REQUEST['method'];}
		if (isset($_REQUEST['parameters']))	{ $parameters = $_REQUEST['parameters'];}

		try 
		{
			$api_ext = new API_extension();

			// Authentification avec le bearer token
			if($method == 'Token') 
			{
				// execCommand prend un array comme parametre
				$result = $api_ext->execCommand($method, array("grant_type" => $_REQUEST['grant_type'], "username" => $_REQUEST['username'], "password" => $_REQUEST['password']));
				echo(json_encode(array('response' =>200, 'token' =>$result )));
			}
			else
			{
				if(isset($token) && $api_ext->decryptToken($token))
				{
					$result = $api_ext->execCommand($method, json_decode($parameters));
					echo(json_encode(array('response' =>200, 'data' =>$result )));
				}
				else
				{
					// Message generique si les informations sont invalides...
    				die(header("HTTP/1.0 404 Not Found"));
				}
			}

		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
	}
	else
	{
		throw new Exception("Error Processing Request", 1);
	}
?>