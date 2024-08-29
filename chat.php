<!-- chat.php -->
<?php
session_start();

// Connect to your database (replace with your actual credentials)
include('includes/dbh.inc.php');

$user_id = $_SESSION['user_id'];
$other_user_id = isset($_GET['other_user_id']) ? $_GET['other_user_id'] : (isset($_POST['other_user_id']) ? $_POST['other_user_id'] : null);

// Query the database to retrieve the profile picture file path
$sql = "SELECT profile_picture FROM users WHERE user_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
}

// Function to fetch chat messages
function fetchChatMessages($conn, $user_id, $other_user_id)
{
    // Implement your database query to fetch chat messages
    // You might want to order by timestamp to display messages in chronological order
    $sql = "SELECT * FROM private_messages
            WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
            ORDER BY timestamp";

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $user_id, $other_user_id, $other_user_id, $user_id);
    $stmt->execute();

    $result = $stmt->get_result();

    $messages = array();

    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    $stmt->close();

    return $messages;
}

// Fetch chat messages from the database
$messages = fetchChatMessages($conn, $user_id, $other_user_id);

// Function to send a message
function sendMessageToDatabase($conn, $user_id, $other_user_id, $message_text)
{
    // Sanitize and escape inputs to prevent SQL injection
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $other_user_id = mysqli_real_escape_string($conn, $other_user_id);
    $message_text = mysqli_real_escape_string($conn, $message_text);

    // Insert the message into the database
    $sql = "INSERT INTO private_messages (sender_id, receiver_id, message_text, timestamp) VALUES (?, ?, ?, NOW())";

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $user_id, $other_user_id, $message_text);
    $result = $stmt->execute();

    // Check if the query was successful
    if ($result) {
        return true;
    } else {
        // Handle the error (you might want to log it or display a user-friendly message)
        return false;
    }
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user IDs and message from the POST data
    $message_text = isset($_POST['message_text']) ? $_POST['message_text'] : '';

    // Check if the message is not empty
    if (!empty($message_text)) {
        // Call the sendMessageToDatabase function
        if (sendMessageToDatabase($conn, $user_id, $other_user_id, $message_text)) {
            // Message sent successfully
            // Redirect to the same page to avoid form resubmission on page refresh
            header("Location: private_chat.php?other_user_id=$other_user_id");
            exit();
        } else {
            // Error sending message
            echo '<script>alert("Error sending message. Please try again.");</script>';
        }
    } else {
        // Empty message
        echo '<script>alert("Message cannot be empty.");</script>';
    }
}

foreach ($messages as $message) {
    $isCurrentUser = ($message['sender_id'] == $user_id);
    $messageClass = $isCurrentUser ? 'sent' : 'received';

    echo '<div class="message ' . $messageClass . '">';
    echo '<p>' . $message['message_text'] . '</p> <br>';
    echo '<span>' . $message['timestamp'] . '</span>';
    echo '</div>';
}