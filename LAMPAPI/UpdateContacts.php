<?php
	require_once 'utils.php';
	require_once 'db_connect.php';

	$inData = getRequestInfo();

	// Check if required fields are present
	if (!isset($inData["id"]) || !isset($inData["newFirstName"]) || !isset($inData["newLastName"]) ||
	    !isset($inData["phoneNumber"]) || !isset($inData["emailAddress"])) {
		$conn->close();
		returnWithError("Missing required fields", 400);
		exit();
	}

	$phoneNumber = $inData["phoneNumber"];
	$emailAddress = $inData["emailAddress"];
	$newFirst = $inData["newFirstName"];
	$newLast = $inData["newLastName"];
	$id = $inData["id"];

	$stmt = $conn->prepare("UPDATE Contacts SET FirstName = ?, LastName=?, Phone= ?, Email= ? WHERE ID= ?");
	if (!$stmt) {
		$conn->close();
		returnWithError("Failed to prepare statement: " . $conn->error);
		exit();
	}

	if (!$stmt->bind_param("ssssi", $newFirst, $newLast, $phoneNumber, $emailAddress, $id)) {
		$stmt->close();
		$conn->close();
		returnWithError("Failed to bind params");
		exit();
	}

	if ($stmt->execute()) {
		returnWithMessage("Successfully updated contact");
	} else {
		returnWithError("Failed to update contact: " . $stmt->error);
	}

	$stmt->close();
	$conn->close();
?>
