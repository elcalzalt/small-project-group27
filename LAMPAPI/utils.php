<?php
	function getRequestInfo() {
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson($obj, $responseCode) {
		http_response_code($responseCode);
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError($err, $responseCode = 500) {
		$retValue = ["error" => $err];
		sendResultInfoAsJson(json_encode($retValue), $responseCode);
	}
	
	function returnWithInfo($data, $responseCode = 200) {
		$retValue = $data;
		$retValue["error"] = "";
		sendResultInfoAsJson(json_encode($retValue), $responseCode);
	}
	
	function returnWithMessage($message, $responseCode = 200) {
		returnWithInfo(["message" => $message], $responseCode);
	}
?>