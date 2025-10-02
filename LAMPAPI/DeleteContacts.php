<?php
    require_once 'utils.php';
    require_once 'db_connect.php';

    $inData = getRequestInfo();

    // Check if required fields are present
    if (!isset($inData["id"])) {
        $conn->close();
        returnWithError("Missing required fields", 400);
        exit();
    }

    $id = $inData["id"];

    $stmt = $conn->prepare("DELETE FROM Contacts WHERE ID = ?");
    if (!$stmt) {
        $conn->close();
        returnWithError("Failed to prepare statement: " . $conn->error);
        exit();
    }

    if (!$stmt->bind_param("i", $id)) {
        $stmt->close();
        $conn->close();
        returnWithError("Failed to bind params");
        exit();
    }

    if ($stmt->execute()) {
        returnWithMessage("Successfully deleted contact");
    } else {
        returnWithError("Failed to delete contact: " . $stmt->error);
    }
    $stmt->close();
    $conn->close();
?>
