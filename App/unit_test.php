<?php
	require_once ('./src/autoloader.php');
	/**
	 * 
	 */
	class unit_test
	{
		public $token;
		public $id;

		function __construct()
		{
			$this->token = $this->getToken();
		}

		private function getToken()
		{
        	$res = file_get_contents('http://localhost/API?method=Token&grant_type=password&username='.CONST_USERNAME.'&password='.CONST_PASSWORD);
        	$resArray = json_decode($res, true);

        	return $resArray['token'];
		}

		function Token()
		{
			try
			{
	        	$res = file_get_contents('http://localhost/API?method=Token&grant_type=password&username='.CONST_USERNAME.'&password='.CONST_PASSWORD);
	        	$resArray = json_decode($res, true);

	        	return assert($resArray['response'] == '200') && assert(!empty($resArray['token']));
        	}
        	catch(Exception $e)
        	{
        		return false;
        	}
		}

		function Get()
		{
			try
			{
	        	$res = file_get_contents("http://localhost/API?token=".$this->token);
	        	$resArray = json_decode($res, true);

	        	return assert($resArray['response'] == '200');
	        }
	        catch(Exception $e)
	        {
	        	return false;
	        }
		}

		function Post($nom, $localisation, $espace_gb)
		{
			try
			{
				$parameters = json_encode(array('nom'=> $nom, 'localisation' => $localisation, 'espace_gb' => $espace_gb), JSON_UNESCAPED_SLASHES);
	        	$res = file_get_contents('http://localhost/API?token='.$this->token.'&method=Post&parameters='.urlencode($parameters));
	        	$resArray = json_decode($res, true);

	        	$this->id = $resArray['data'];

	        	return assert($resArray['response'] == '200' && $resArray['data'] != null);
        	}
			catch(Exception $e)
	        {
	        	return false;
	        }
		}

		function Put($nom, $localisation, $espace_gb)
		{
			try
			{
				$parameters = json_encode(array('id' => $this->id, 'nom'=> $nom, 'localisation' => $localisation, 'espace_gb' => $espace_gb), JSON_UNESCAPED_SLASHES);
	        	$res = file_get_contents('http://localhost/API?token='.$this->token.'&method=Put&parameters='.urlencode($parameters));
	        	$resArray = json_decode($res, true);

		    	return assert($resArray['response'] == '200');
        	}
			catch(Exception $e)
	        {
	        	return false;
	        }
		}

		function Delete()
		{
			try
			{
				$parameters = json_encode(array('id' => $this->id), JSON_UNESCAPED_SLASHES);
	        	$res = file_get_contents('http://localhost/API?token='.$this->token.'&method=Delete&parameters='.urlencode($parameters));
	        	$resArray = json_decode($res, true);

	        	return assert($resArray['response'] == '200');
        	}
			catch(Exception $e)
	        {
	        	return false;
	        }
		}
	}

	$unit_test = new unit_test();
?>
<html>
	<head>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
	</head>
	<body>
		<br />
		<div class="row">
			<div class="offset-sm-2 col-sm-8">
				<div class="alert alert-warning">Si le champs Assert est vide - s.v.p rafraîchir la page. Merci</div>
				<div class="card">
					<div class="card-header">Tests unitaires <a href="./index" style="float: right;">Retour</a></div>
					<div class="card-body">
						<table class="table">
							<thead>
								<tr>
									<th>Method</th>
									<th>Assert</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Token</td>
									<td><?= $unit_test->Token(); ?></td>
								</tr>
								<tr>
									<td>GET</td>
									<td><?= $unit_test->GET(); ?></td>
								</tr>
								<tr>
									<td>POST</td>
									<td><?= $unit_test->POST('AWS_1','Montreal', 500); ?></td>
								</tr>
								<tr>
									<td>PUT</td>
									<td><?= $unit_test->PUT('AWS_2','Laval', 500); ?></td>
								</tr>
								<tr>
									<td>DELETE</td>
									<td><?= $unit_test->DELETE(); ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>