<?php
	require_once 'utils.php';
	require_once 'db_connect.php';

	$inData = getRequestInfo();

	$firstName = $inData["firstName"];
	$lastName = $inData["lastName"];
	$phoneNumber = $inData["phoneNumber"];
	$emailAddress = $inData["emailAddress"];
	$userId = $inData["userId"];

	$stmt = $conn->prepare("INSERT into Contacts (FirstName, LastName, Phone, Email, UserID) VALUES(?,?,?,?,?)");
	$stmt->bind_param("ssssi", $firstName, $lastName, $phoneNumber, $emailAddress, $userId);
	if ($stmt->execute()) {
		returnWithMessage("Successfully added contact");
	} else {
		returnWithError("Failed to insert contact: " . $stmt->error);
	}
	$stmt->close();
	$conn->close();
?>
