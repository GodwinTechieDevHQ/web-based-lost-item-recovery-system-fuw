<!-- transfer_confirm.php -->
<?php
session_start();
include('../includes/dbh.inc.php');
// // Include necessary files and perform authentication
include("header.php");
include("../includes/login_check.inc.php");

// // Get data from the POST request
$user_id = $_SESSION['user_id'];
$other_user_id = isset($_GET['other_user_id']) ? $_GET['other_user_id'] : (isset($_POST['other_user_id']) ? $_POST['other_user_id'] : null);

$itemId = $_POST['item_id'];

$transactionStatus = insertTransaction($_SESSION['user_id'], $_SESSION['other_user_id'], $itemId, 'pending');

// Return a response to the client (success or error message)
if ($transactionStatus) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error inserting transaction.']);
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

header("Location: profile.php");
include("footer.php");
?>