<?php
    require_once 'utils.php';
    require_once 'db_connect.php';

    $inData = getRequestInfo();

    $userId = $inData["userId"];
    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];

    $stmt = $conn->prepare("DELETE FROM Contacts WHERE FirstName = ? AND LastName = ? AND UserID = ?");
    $stmt->bind_param("ssi", $firstName, $lastName, $userId);
    if ($stmt->execute()) {
        returnWithMessage("Successfully deleted contact");
    } else {
        returnWithError("Failed to delete contact: " . $stmt->error);
    }
    $stmt->close();
    $conn->close();
?>
