<?php
	function getRequestInfo() {
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson($obj) {
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError($err, $additionalFields = []) {
		$retValue = ["error" => $err];
		
		// Add any additional fields that were passed
		foreach ($additionalFields as $key => $value) {
			$retValue[$key] = $value;
		}
		
		// If no additional fields were specified, use default empty values for common fields
		if (empty($additionalFields)) {
			$retValue["id"] = 0;
			$retValue["firstName"] = "";
			$retValue["lastName"] = "";
		}
		
		sendResultInfoAsJson(json_encode($retValue));
	}
	
	function returnWithInfo($data) {
		$retValue = $data;
		$retValue["error"] = "";
		sendResultInfoAsJson(json_encode($retValue));
	}
	
	function returnWithMessage($message) {
		returnWithInfo(["message" => $message]);
	}
?>