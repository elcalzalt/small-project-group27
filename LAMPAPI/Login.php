<?php
	require_once 'utils.php';
	require_once 'db_connect.php';

	$inData = getRequestInfo();
	
	$id = 0;
	$firstName = "";
	$lastName = "";

	$stmt = $conn->prepare("SELECT ID,firstName,lastName FROM Users WHERE Login=? AND Password =?");
	$stmt->bind_param("ss", $inData["login"], $inData["password"]);
	$stmt->execute();
	$result = $stmt->get_result();

	if( $row = $result->fetch_assoc()  )
	{
		$data = [
			"id" => $row['ID'],
			"firstName" => $row['firstName'],
			"lastName" => $row['lastName']
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