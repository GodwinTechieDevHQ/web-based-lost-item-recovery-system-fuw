<?php
include("header.php");
include("includes/login_check.inc.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to your database (replace these with your actual database credentials)
    include("includes/dbh.inc.php");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the transaction_id from the AJAX request
    $transactionId = $_POST["transaction_id"];

    // Fetch the corresponding item_id for the transaction
    $getTransactionItemSql = "SELECT item_id FROM transactions WHERE transaction_id = ?";
    $stmtGetTransactionItem = $conn->prepare($getTransactionItemSql);
    $stmtGetTransactionItem->bind_param("i", $transactionId);
    $stmtGetTransactionItem->execute();
    $stmtGetTransactionItem->bind_result($itemId);
    $stmtGetTransactionItem->fetch();
    $stmtGetTransactionItem->close();

    // Update the item's owner_id in the lost_items table
    $updateLostItemsSql = "UPDATE lost_items SET owner_id = ? WHERE item_id = ?";
    $stmtLostItems = $conn->prepare($updateLostItemsSql);
    $stmtLostItems->bind_param("ii", $_SESSION["user_id"], $itemId);

    // Update the status in the transactions table
    $updateTransactionsSql = "UPDATE transactions SET status = 'successful' WHERE transaction_id = ?";
    $stmtTransactions = $conn->prepare($updateTransactionsSql);
    $stmtTransactions->bind_param("i", $transactionId);

    // Perform both updates in a transaction
    $conn->begin_transaction();
    $success = true;

    if (!$stmtLostItems->execute() || !$stmtTransactions->execute()) {
        $success = false;
        $conn->rollback();
        header("Location:profile.php");
    } else {
        $conn->commit();
    }

    $stmtLostItems->close();
    $stmtTransactions->close();
    $conn->close();

    echo json_encode(['success' => $success]);
} else {
    header("Location: index.php");
    exit();
}
?>