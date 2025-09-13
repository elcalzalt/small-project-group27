<?php
	require_once 'utils.php';
	require_once 'db_connect.php';

	$inData = getRequestInfo();

	$searchCount = 0;

	$stmt = $conn->prepare("SELECT * FROM Users WHERE Login= ?");
	$stmt->bind_param("s", $inData["login"]);
	$stmt->execute();

	$result = $stmt->get_result();

	while($row = $result->fetch_assoc())
	{
		$searchCount++;
	}

	if( $searchCount == 0 )
	{
		returnWithMessage("Login is not taken");
	}
	else
	{
		returnWithError( "Login has been taken" );
	}

	$stmt->close();
	$conn->close();
?>
