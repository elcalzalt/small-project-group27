<?php
	require_once 'utils.php';
	require_once 'db_connect.php';

	$inData = getRequestInfo();

	// Check if required fields are present
	if (!isset($inData["firstName"]) || !isset($inData["lastName"]) ||
	    !isset($inData["login"]) || !isset($inData["password"])) {
		$conn->close();
		returnWithError("Missing required fields", 400);
		exit();
	}

	$firstName = $inData["firstName"];
	$lastName = $inData["lastName"];
	$login = $inData["login"];
	$password = $inData["password"];
    $hashedPassword = md5($password);

	$stmt = $conn->prepare("SELECT * FROM Users WHERE Login=?");
	if (!$stmt) {
		$conn->close();
		returnWithError("Failed to prepare statement: " . $conn->error);
		exit();
	}

	if (!$stmt->bind_param("s", $login)) {
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
	$rows = mysqli_num_rows($result);
	
	if ($rows == 0) {
		$stmt = $conn->prepare("INSERT into Users (FirstName,LastName,Login,Password) VALUES (?,?,?,?)");
		if (!$stmt) {
			$conn->close();
			returnWithError("Failed to prepare insert statement: " . $conn->error);
			exit();
		}

		if (!$stmt->bind_param("ssss", $firstName, $lastName, $login, $hashedPassword)) {
			$stmt->close();
			$conn->close();
			returnWithError("Failed to bind params for insert");
			exit();
		}

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
	} else {
		returnWithError("There already exists a user with the same login", 409);
	}

	$stmt->close();
	$conn->close();
?>