<?php
// claim_item.php
include("includes/dbh.inc.php"); // Include your database connection file

// Check if the transaction ID is provided via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["transaction_id"])) {
    // Get the transaction ID from the POST data
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
        echo json_encode(['success' => false]);
    } else {
        $conn->commit();
        echo json_encode(['success' => true]);
    }

    $stmtLostItems->close();
    $stmtTransactions->close();
    $conn->close();
} else {
    // If transaction ID is not provided, return an error response
    echo json_encode(['success' => false, 'error' => 'Transaction ID not provided']);
}
?>