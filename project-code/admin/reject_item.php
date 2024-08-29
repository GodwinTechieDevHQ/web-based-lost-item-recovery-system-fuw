<?php
// reject_item.php

include("includes/dbh.inc.php"); // Include your database connection file

// Check if the transaction ID is provided via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["transaction_id"])) {
    // Get the transaction ID from the POST data
    $transactionId = $_POST["transaction_id"];

    // Prepare and execute the DELETE query to remove the transaction from the database
    $deleteTransactionSql = "DELETE FROM transactions WHERE transaction_id = ?";
    $stmt = $conn->prepare($deleteTransactionSql);
    $stmt->bind_param("i", $transactionId);
    $success = $stmt->execute();

    // Close the statement
    $stmt->close();

    // Check if the deletion was successful
    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    // If transaction ID is not provided, return an error response
    echo json_encode(['success' => false, 'error' => 'Transaction ID not provided']);
}
?>