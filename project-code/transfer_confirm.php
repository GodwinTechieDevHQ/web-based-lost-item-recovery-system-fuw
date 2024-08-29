<?php
// transfer_confirm.php
session_start();
include('includes/dbh.inc.php');

// Get data from the POST request
$user_id = $_SESSION['user_id'];
$other_user_id = isset($_POST['other_user_id']) ? $_POST['other_user_id'] : null;
$itemId = $_POST['item_id'];

// Check if the transaction already exists
if (transactionExists($user_id, $other_user_id, $itemId)) {
    echo json_encode(['success' => false, 'message' => 'Transaction already exists.']);
} else {
    // Insert the transaction if it doesn't exist
    $transactionStatus = insertTransaction($_SESSION['user_id'], $_SESSION['other_user_id'], $itemId, 'pending');

    // Return a response to the client (success or error message)
    if ($transactionStatus) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error inserting transaction.']);
    }
}

// Function to check if a transaction already exists
function transactionExists($senderId, $receiverId, $itemId) {
    global $conn;

    $sql = "SELECT COUNT(*) AS count FROM transactions WHERE sender_id = ? AND receiver_id = ? AND item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $senderId, $receiverId, $itemId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $count = $row['count'];
    $stmt->close();

    return $count > 0;
}

// Function to insert a transaction into the database
function insertTransaction($senderId, $receiverId, $itemId, $status) {
    // Implement your database query to insert a transaction
    // Use prepared statements to prevent SQL injection
    global $conn;

    $sql = "INSERT INTO transactions (sender_id, receiver_id, item_id, status) VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $senderId, $receiverId, $itemId, $status);
    $result = $stmt->execute();

    $stmt->close();

    return $result;
}
?>