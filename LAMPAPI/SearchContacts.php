<?php
	require_once 'utils.php';
	require_once 'db_connect.php';

	$inData = getRequestInfo();

	// Check if required fields are present
	if (!isset($inData["search"]) || !isset($inData["userId"])) {
		$conn->close();
		returnWithError("Missing required fields", 400);
		exit();
	}

	$searchResults = [];

	$stmt = $conn->prepare("SELECT FirstName, LastName, Phone, Email, UserID, ID FROM Contacts WHERE (FirstName like ? OR LastName like?) AND UserID=?");
	if (!$stmt) {
		$conn->close();
		returnWithError("Failed to prepare statement: " . $conn->error);
		exit();
	}

	$searchTerm = "%" . $inData["search"] . "%";
	if (!$stmt->bind_param("sss", $searchTerm, $searchTerm, $inData["userId"])) {
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
	while($row = $result->fetch_assoc())
	{
		$searchResults[] = $row;
	}

	$data = ["results" => $searchResults];
	returnWithInfo( $data);
	
	$stmt->close();
	$conn->close();
?>
