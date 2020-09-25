<?php
	class MySqlConnect
	{
		public $conn;

		function __construct($servername, $username, $password, $table)
		{
			if($this->conn == null)
			{
				$this->conn = new mysqli($servername, $username, $password, $table);
			}
		}
	}
?>