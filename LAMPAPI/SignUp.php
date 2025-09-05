<?php
	require_once 'utils.php';
	require_once 'db_connect.php';

	$inData = getRequestInfo();
	
	$firstName = $inData["firstName"];
	$lastName = $inData["lastName"];
	$login = $inData["login"];
	$password = $inData["password"];

	$stmt = $conn->prepare("INSERT into Users (FirstName,LastName,Login,Password) VALUES (?,?,?,?)");
	$stmt->bind_param("ssss", $firstName, $lastName, $login, $password);
	
	if ($stmt->execute()) {
		// Successfully inserted
		$data = [
			"id" => $conn->insert_id,
			"firstName" => $firstName,
			"lastName" => $lastName
		];
		returnWithInfo($data);
	} else {
		// Error occurred
		returnWithError("Failed to create user: " . $stmt->error);
	}
	
	$stmt->close();
	$conn->close();
?>