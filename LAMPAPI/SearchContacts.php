<?php
	require_once 'utils.php';
	require_once 'db_connect.php';

	$inData = getRequestInfo();

	$searchResults = [];
	$searchCount = 0;

	$stmt = $conn->prepare("SELECT FirstName, LastName, Phone, Email, UserID, ID FROM Contacts WHERE (FirstName like ? OR LastName like?) AND UserID=?");
	$searchTerm = "%" . $inData["search"] . "%";
	$stmt->bind_param("sss", $searchTerm, $searchTerm, $inData["userId"]);
	$stmt->execute();

	$result = $stmt->get_result();

	while($row = $result->fetch_assoc())
	{
		$searchResults[] = $row;
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
