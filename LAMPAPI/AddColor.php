<?php
	require_once 'utils.php';
	require_once 'db_connect.php';

	$inData = getRequestInfo();
	
	$color = $inData["color"];
	$userId = $inData["userId"];

	$stmt = $conn->prepare("INSERT into Colors (UserId,Name) VALUES(?,?)");
	$stmt->bind_param("ss", $userId, $color);
	$stmt->execute();
	$stmt->close();
	$conn->close();
	returnWithError("");
?>