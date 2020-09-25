<?php 
	require_once ('./src/autoloader.php');

    // check to see if our cookie is set
    if (empty($_COOKIE['token'])) {
        $_COOKIE['token'] = null;
    }

    if (!isset($_COOKIE['token']) && !empty($_POST)) {
        setInitialCookie();
    }

    function setInitialCookie() {
        $postData .= 'method=Token&grant_type=password&username='.urlencode($_POST['username']).'&password='.urlencode($_POST['password']);
        $res = file_get_contents("http://localhost/API?".$postData);
        $resArray = json_decode($res, true);

        if ($resArray['response'] == 200) {
			setCookie('token', $resArray['token']);

			// Petit 'hack' pour se logger
			header("Refresh:0");
        }
    }
?>
<html>
	<head>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
	</head>
	<body>
	<?php if (isset($_COOKIE['token'])) { ?>
		<br />
		<div class="row">
			<div class="offset-sm-2 col-sm-8">
				<div class="card">
					<div class="card-header">Serveurs <a href="javascript:;" style="float: right;" id="ajouter" data-toggle="modal" data-target="#exampleModal">Ajouter</a></div>
					<div class="card-body">
						<table class="table">
							<thead>
								<tr>
									<th>Nom</th>
									<th>Localisation</th>
									<th>Espace (Gb)</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody id="tbody">	
							</tbody>
						</table>
						<button class="btn btn-primary" id="deconnexion">Deconnexion</button>
						<a href="./unit_test.php" class="btn btn-primary">Tests unitaitres</a>
					</div>
				</div>
			</div>
		</div>
		<!-- Create Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">Ajout d'un serveur</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
					<div id="_nom" class="form-group">
						<label class="control-label" for="name">
							Nom
						</label>
						<input type="text" name="nom" id="nom" class="form-control">
					</div>
					<div id="_localisation" class="form-group">
						<label class="control-label" for="comments">
							Localisation
						</label>
						<input type="text" id="localisation" name="localisation" class="form-control">
					</div>
					<div id="_espace" class="form-group">
						<label class="control-label" for="comments">
							Espace (Gb)
						</label>
						<input type="number" id="espace_gb" name="espace_gb" class="form-control">
					</div>
				</div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
		        <button id="ajouter-serveur" type="button" class="btn btn-primary" data-dismiss="modal">Soumettre</button>
		      </div>
		    </div>
		  </div>
		</div>
		<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">Édition d'un serveur</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		      		<input type="hidden" name="id_edit" id="id_edit">
					<div id="_nom" class="form-group">
						<label class="control-label" for="name">
							Nom
						</label>
						<input type="text" name="nom_edit" id="nom_edit" class="form-control">
					</div>
					<div id="_localisation" class="form-group">
						<label class="control-label" for="localisation_edit">
							Localisation
						</label>
						<input type="text" id="localisation_edit" name="localisation_edit" class="form-control">
					</div>
					<div id="_espace" class="form-group">
						<label class="control-label" for="comments">
							Espace (Gb)
						</label>
						<input type="number" id="espace_gb_edit" name="espace_gb_edit" class="form-control">
					</div>
				</div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
		        <button id="edit-serveur" type="button" class="btn btn-primary" data-dismiss="modal">Soumettre</button>
		      </div>
		    </div>
		  </div>
		</div>
		<script>
			$(function()
			{
				function ajax(method, parameters)
				{
				    $.ajax(
					{
						url: "http://localhost/API",
						data : { token : "<?= $_COOKIE['token'] ?>", method: method, parameters: parameters },
				    	complete: function(result){
				    		window.location.reload();
				    	}
					});
				}

			    $.ajax(
				{
					url: "http://localhost/API",
					data : { token : "<?= $_COOKIE['token'] ?>", method: 'Get', parameters: '' },
			    	success: function(result){
			      		for (var i = 0; i < result.data.length; i++) {
			      			$('#tbody').append("<tr><td>"+ result.data[i].nom +"</td><td>"+ result.data[i].localisation +"</td><td>"+ result.data[i].espace_gb +"</td><td><a class='edition' data-id=" + result.data[i].id + " href='javascript:;'>Édition</a> <a class='suppression' href='javascript:;' data-id=" + result.data[i].id + ">Suppression<a/></td></tr>");
			      		}
			    	},
			    	error: function()
			    	{
			    		$.removeCookie("token", null, { path: '/' });
			    		window.location.reload();
			    	}
				});

				$('#deconnexion').on('click', function(e)
				{
		    		$.removeCookie("token", null, { path: '/' });
		    		window.location.reload();
				});

				$('#ajouter-serveur').on('click', function(e)
				{
					var parameters = {};
					parameters["nom"] = $('#nom').val();
					parameters["localisation"] = $('#localisation').val();
					parameters["espace_gb"] = $('#espace_gb').val();

					ajax('Post', JSON.stringify(parameters));
				});

				$(document).on('click', '.edition', function(e)
				{
					$('#id_edit').val($(this).data('id'));

					$('#nom_edit').val($(this).parents('tr').find('td:nth-child(1)').html());
					$('#localisation_edit').val($(this).parents('tr').find('td:nth-child(2)').html());
					$('#espace_gb_edit').val($(this).parents('tr').find('td:nth-child(3)').html());

					$('#editModal').modal('toggle');
				});

				$('#edit-serveur').on('click', function(e)
				{
					var parameters = {};
					parameters["id"] = $('#id_edit').val();
					parameters["nom"] = $('#nom_edit').val();
					parameters["localisation"] = $('#localisation_edit').val();
					parameters["espace_gb"] = $('#espace_gb_edit').val();

					ajax('Put', JSON.stringify(parameters));
				});


				$(document).on('click', '.suppression', function(e)
				{
					var parameters = {};
					parameters["id"] = $(this).data('id');

					ajax('Delete', JSON.stringify(parameters));
				});
			});
		</script>
	<?php }
	else { ?>
		<br />
		<div class="row">
			<div class="offset-sm-2 col-sm-8">
				<div class="card">
					<div class="card-header">Connexion</div>
					<div class="card-body">
						<form action="./index.php" method="post" accept-charset="utf-8">
						    <div id="_name" class="form-group">
						        <label class="control-label" for="name">
						            Utilisateur
						        </label>
						        <input type="text" name="username" id="username" class="form-control" required="required"> 
						    </div>
						    <div id="_password" class="form-group">
						        <label class="control-label" for="comments">
						            Mot de passe
						        </label>
						        <input type="password" id="password" name="password" class="form-control" required="required">
						    </div>
						    <div id="_button" class="form-group">
						        <button type="submit" name="button" id="button" class="btn btn-primary col-sm-12">Soumettre</button>
						    </div>
						</form>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	</body>
</html>