<?php
	require_once 'utils.php';
	require_once 'db_connect.php';

	$inData = getRequestInfo();

	// Check if required fields are present
	if (!isset($inData["login"]) || !isset($inData["password"])) {
		$conn->close();
		returnWithError("Missing required fields", 400);
		exit();
	}

	$id = 0;
	$firstName = "";
	$lastName = "";

	$hashedPassword = md5($inData["password"]);
	$stmt = $conn->prepare("SELECT ID,FirstName,LastName FROM Users WHERE Login=? AND Password =?");
	if (!$stmt) {
		$conn->close();
		returnWithError("Failed to prepare statement: " . $conn->error);
		exit();
	}

	if (!$stmt->bind_param("ss", $inData["login"], $hashedPassword)) {
		$stmt->close();
		$conn->close();
		returnWithError("Failed to bind params");
		exit();
	}

	if (!$stmt->execute()) {
		$stmt->close();
		$conn->close();
		returnWithError("Failed to execute statement: " . $stmt->error);
		exit();
	}

	$result = $stmt->get_result();
	if( $row = $result->fetch_assoc()  )
	{
		$data = [
			"id" => $row['ID'],
			"firstName" => $row['FirstName'],
			"lastName" => $row['LastName']
		];
		returnWithInfo( $data );
	}
	else
	{
		returnWithError("Not a valid login", 401);
	}
	$stmt->close();
	$conn->close();
?>
