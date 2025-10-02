<?php
	require_once 'utils.php';
	require_once 'db_connect.php';

	$inData = getRequestInfo();

	// Check if required fields are present
	if (!isset($inData["firstName"]) || !isset($inData["lastName"]) ||
	    !isset($inData["phoneNumber"]) || !isset($inData["emailAddress"]) ||
	    !isset($inData["userId"])) {
		$conn->close();
		returnWithError("Missing required fields", 400);
		exit();
	}

	$firstName = $inData["firstName"];
	$lastName = $inData["lastName"];
	$phoneNumber = $inData["phoneNumber"];
	$emailAddress = $inData["emailAddress"];
	$userId = $inData["userId"];

	$stmt = $conn->prepare("INSERT into Contacts (FirstName, LastName, Phone, Email, UserID) VALUES(?,?,?,?,?)");
	if (!$stmt) {
		$conn->close();
		returnWithError("Failed to prepare statement: " . $conn->error);
		exit();
	}

	if (!$stmt->bind_param("ssssi", $firstName, $lastName, $phoneNumber, $emailAddress, $userId)) {
		$stmt->close();
		$conn->close();
		returnWithError("Failed to bind params");
		exit();
	}
	if ($stmt->execute()) {
		returnWithMessage("Successfully added contact");
	} else {
		returnWithError("Failed to insert contact: " . $stmt->error);
	}
	$stmt->close();
	$conn->close();
?>
