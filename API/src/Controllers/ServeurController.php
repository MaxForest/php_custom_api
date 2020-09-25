<?php
	class ServeurController
	{
		protected $model;
		
		public function __construct() {
			$this->model = new Serveur();
		}
		
		function Get()
		{
			return $this->model->Get();
		}

		function Post($parameters)
		{
			$nom = $parameters->nom;
			$localisation = $parameters->localisation;
			$espace_gb = $parameters->espace_gb;

			return $this->model->Post($nom, $localisation, $espace_gb);
		}

		function Put($parameters)
		{
			$id = $parameters->id;
			$nom = $parameters->nom;
			$localisation = $parameters->localisation;
			$espace_gb = $parameters->espace_gb;

			return $this->model->Put($id, $nom, $localisation, $espace_gb);
		}

		function Delete($parameters)
		{
			return $this->model->Delete($parameters->id);
		}
	}
?>