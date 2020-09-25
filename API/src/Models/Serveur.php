<?php
	class Serveur
	{
		protected $mysql;

		function __construct()
		{
			$this->mysql = new MySqlConnect(CONST_DB_HOST, CONST_DB_USERNAME, CONST_DB_PASSWORD, CONST_DB);
		}

		function Get()
		{
			$json = array();
			$result = $this->mysql->conn->query("select * from serveurs");

			while($row = $result->fetch_assoc()) {
				array_push($json, $row);
			}

			return $json;
		}

		function Post($nom, $localisation, $espace_gb)
		{
			$stmt = $this->mysql->conn->prepare("INSERT INTO serveurs(nom, localisation, espace_gb) VALUES(?, ?, ?)");
			$stmt->bind_param("sss", $nom, $localisation, $espace_gb);
			$stmt->execute();

			return $stmt->insert_id;
		}

		function Put($id, $nom, $localisation, $espace_gb)
		{

			$stmt = $this->mysql->conn->prepare("UPDATE serveurs SET nom=?, localisation=?, espace_gb=? WHERE id=?");
			$stmt->bind_param("sssi", $nom, $localisation, $espace_gb, $id);
			$stmt->execute();
		}

		function Delete($id)
		{
			$stmt = $this->mysql->conn->prepare("DELETE FROM serveurs WHERE id = ?");
			$stmt->bind_param("s", $id);
			$stmt->execute();
		}

	    function __destruct() {
            $this->mysql->conn->close();
    	}
	}

?>