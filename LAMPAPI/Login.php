<?php
	require_once 'utils.php';
	require_once 'db_connect.php';

	$inData = getRequestInfo();

	$id = 0;
	$firstName = "";
	$lastName = "";

	$hashedPassword = md5($inData["password"]);
	$stmt = $conn->prepare("SELECT ID,FirstName,LastName FROM Users WHERE Login=? AND Password =?");
	$stmt->bind_param("ss", $inData["login"], $hashedPassword);
	$stmt->execute();
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
		returnWithError("No Records Found");
	}

	$stmt->close();
	$conn->close();
?>
