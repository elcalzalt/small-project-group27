<?php
	require_once 'utils.php';
	
	$host = "localhost";
	$username = "TheBeast";
	$password = "WeLoveCOP4331";
	$database = "COP4331";

	$conn = new mysqli($host, $username, $password, $database);

	if ($conn->connect_error) {
		returnWithError($conn->connect_error);
		exit();
	}
?>