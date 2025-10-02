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

	// Check if userId exists in the Users table
	$userCheckStmt = $conn->prepare("SELECT ID FROM Users WHERE ID = ?");
	if (!$userCheckStmt) {
		$conn->close();
		returnWithError("Failed to prepare user check statement: " . $conn->error);
		exit();
	}

	if (!$userCheckStmt->bind_param("i", $inData["userId"])) {
		$userCheckStmt->close();
		$conn->close();
		returnWithError("Failed to bind user check params");
		exit();
	}

	if (!$userCheckStmt->execute()) {
		$userCheckStmt->close();
		$conn->close();
		returnWithError("Failed to execute user check statement: " . $userCheckStmt->error);
		exit();
	}

	$userResult = $userCheckStmt->get_result();
	if ($userResult->num_rows == 0) {
		$userCheckStmt->close();
		$conn->close();
		returnWithError("Invalid userId", 404);
		exit();
	}
	$userCheckStmt->close();

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
