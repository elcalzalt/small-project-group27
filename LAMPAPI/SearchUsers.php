<?php
	require_once 'utils.php';
	require_once 'db_connect.php';

	$inData = getRequestInfo();

	// Check if required fields are present
	if (!isset($inData["login"])) {
		$conn->close();
		returnWithError("Missing required fields", 400);
		exit();
	}

	$stmt = $conn->prepare("SELECT * FROM Users WHERE Login= ?");
	if (!$stmt) {
		$conn->close();
		returnWithError("Failed to prepare statement: " . $conn->error);
		exit();
	}

	if (!$stmt->bind_param("s", $inData["login"])) {
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
	$searchCount = mysqli_num_rows($result);

	if( $searchCount == 0 )
	{
		returnWithMessage("Login is not taken");
	}
	else
	{
		returnWithError( "Login has been taken", 409);
	}
	$stmt->close();
	$conn->close();
?>
