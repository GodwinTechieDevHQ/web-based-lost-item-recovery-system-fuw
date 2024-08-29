<!-- fetch_messages.php -->

<?php
// Include your database connection
include('includes/dbh.inc.php');

// Retrieve user IDs from the AJAX request and sanitize them to prevent injection
$user_id = intval($_GET['user_id']);
$other_user_id = intval($_GET['other_user_id']);

// Fetch new messages based on the user IDs
$messages = fetchNewMessages($user_id, $other_user_id);

// Prepare and send JSON response with the messages
$response = array('messages' => includeMessages($messages, $user_id));
echo json_encode($response);

// Function to fetch new messages from the database
function fetchNewMessages($user_id, $other_user_id) {
    global $conn; // Access the global database connection

    // SQL query to get messages between the two users
    $sql = "SELECT sender_id, message_text, timestamp FROM private_messages
            WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
            AND timestamp > NOW() - INTERVAL 1 DAY"; // Fetch messages from the last day
    $stmt = $conn->prepare($sql); // Prepare the SQL statement
    $stmt->bind_param("iiii", $user_id, $other_user_id, $other_user_id, $user_id); // Bind user IDs to the query
    $stmt->execute(); // Execute the query
    $result = $stmt->get_result(); // Get the result

    $messages = array();
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row; // Add each message to the array
    }

    return $messages; // Return the array of messages
}

// Function to format messages for display
function includeMessages($messages, $user_id) {
    ob_start(); // Start output buffering
    foreach ($messages as $message) {
        // Check if the message is from the current user
        $isCurrentUser = ($message['sender_id'] == $user_id);
        $messageClass = $isCurrentUser ? 'sent' : 'received'; // Assign class based on sender

        // Output the message with appropriate class
        echo '<div class="message ' . $messageClass . '">';
        echo '<p>' . htmlspecialchars($message['message_text'], ENT_QUOTES, 'UTF-8') . '</p>'; // Escape message text
        echo '<span>' . htmlspecialchars($message['timestamp'], ENT_QUOTES, 'UTF-8') . '</span>'; // Escape timestamp
        echo '</div>';
    }
    return ob_get_clean(); // Return the buffered output
}
?>
