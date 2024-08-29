<?php
// Include the header and login check scripts
include("header.php");
include("includes/login_check.inc.php");

session_start(); // Start session to access user data

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    include("includes/dbh.inc.php");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error); // Exit if connection fails
    }

    // Get the transaction_id from the POST request
    $transactionId = $_POST["transaction_id"];

    // Prepare and execute query to get item_id for the transaction
    $getTransactionItemSql = "SELECT item_id FROM transactions WHERE transaction_id = ?";
    $stmtGetTransactionItem = $conn->prepare($getTransactionItemSql);
    $stmtGetTransactionItem->bind_param("i", $transactionId); // Bind transaction_id
    $stmtGetTransactionItem->execute();
    $stmtGetTransactionItem->bind_result($itemId); // Get item_id
    $stmtGetTransactionItem->fetch();
    $stmtGetTransactionItem->close();

    // Prepare and execute query to update lost_items with new owner_id
    $updateLostItemsSql = "UPDATE lost_items SET owner_id = ? WHERE item_id = ?";
    $stmtLostItems = $conn->prepare($updateLostItemsSql);
    $stmtLostItems->bind_param("ii", $_SESSION["user_id"], $itemId); // Bind user_id and item_id

    // Prepare and execute query to update the status of the transaction
    $updateTransactionsSql = "UPDATE transactions SET status = 'successful' WHERE transaction_id = ?";
    $stmtTransactions = $conn->prepare($updateTransactionsSql);
    $stmtTransactions->bind_param("i", $transactionId); // Bind transaction_id

    // Start a transaction to ensure both queries succeed
    $conn->begin_transaction();
    $success = true;

    // Execute both updates and handle success or failure
    if (!$stmtLostItems->execute() || !$stmtTransactions->execute()) {
        $success = false; // Set success to false if any query fails
        $conn->rollback(); // Roll back changes if there is an error
        header("Location:profile.php"); // Redirect if there is a failure
    } else {
        $conn->commit(); // Commit changes if all queries are successful
    }

    // Close prepared statements and database connection
    $stmtLostItems->close();
    $stmtTransactions->close();
    $conn->close();

    // Return success status as JSON
    echo json_encode(['success' => $success]);
} else {
    // Redirect to index.php if not a POST request
    header("Location: index.php");
    exit();
}
?>
