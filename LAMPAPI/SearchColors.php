<?php
	require_once 'utils.php';
	require_once 'db_connect.php';

	$inData = getRequestInfo();
	
	$searchResults = [];
	$searchCount = 0;

	$stmt = $conn->prepare("select Name from Colors where Name like ? and UserID=?");
	$colorName = "%" . $inData["search"] . "%";
	$stmt->bind_param("ss", $colorName, $inData["userId"]);
	$stmt->execute();
	
	$result = $stmt->get_result();
	
	while($row = $result->fetch_assoc())
	{
		$searchResults[] = $row["Name"];
		$searchCount++;
	}
	
	if( $searchCount == 0 )
	{
		returnWithError( "No Records Found" );
	}
	else
	{
		$data = ["results" => $searchResults];
		returnWithInfo( $data );
	}
	
	$stmt->close();
	$conn->close();
?>