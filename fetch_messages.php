<!-- fetch_messages.php -->

<?php
// Replace with your actual database connection logic
include('includes/dbh.inc.php');

// Retrieve user IDs from the AJAX request
$user_id = $_GET['user_id'];
$other_user_id = $_GET['other_user_id'];

// Fetch new messages from the database (replace with your actual query)
$messages = fetchNewMessages($user_id, $other_user_id);

// JSON response
$response = array('messages' => includeMessages($messages, $user_id));
echo json_encode($response);

function fetchNewMessages($user_id, $other_user_id) {
    // Replace this with your actual database query to fetch new messages
    // Example: SELECT * FROM private_messages WHERE (sender_id = $user_id AND receiver_id = $other_user_id) OR (sender_id = $other_user_id AND receiver_id = $user_id) AND timestamp > '$last_timestamp';

    // For this example, I'm using a static array. Replace this with your database logic.
    $messages = array(
        array('sender_id' => $user_id, 'message_text' => 'New message!', 'timestamp' => '2023-11-03 16:00:00'),
        // Add more new messages as needed
    );

    return $messages;
}

function includeMessages($messages, $user_id) {
    // Function to include messages in the chat container
    ob_start();
    foreach ($messages as $message) {
        $isCurrentUser = ($message['sender_id'] == $user_id);
        $messageClass = $isCurrentUser ? 'sent' : 'received';

        echo '<div class="message ' . $messageClass . '">';
        echo '<p>' . $message['message_text'] . '</p>';
        echo '<span>' . $message['timestamp'] . '</span>';
        echo '</div>';
    }
    return ob_get_clean();
}
?>