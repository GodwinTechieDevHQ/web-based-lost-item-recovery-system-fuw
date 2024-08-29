<!-- send_messages.inc.php -->
<?php
// Include necessary files
include('functions.inc.php');

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user IDs and message from the POST data
    $user_id = $_SESSION['user_id'];
    $other_user_id = $_POST['other_user_id'];  // Get the other_user_id from the POST data
    $message_text = $_POST['message_text'];

    // Check if the message is not empty
    if (!empty($message_text)) {
        // Call the sendMessage function
        if (sendMessage($user_id, $other_user_id, $message_text)) {
            // Message sent successfully
            $response = array('status' => 'success', 'newMessage' => 'Your formatted new message HTML here.');
        } else {
            // Error sending message
            $response = array('status' => 'error', 'message' => 'Error sending message. Please try again.');
        }
    } else {
        // Empty message
        $response = array('status' => 'error', 'message' => 'Message cannot be empty.');
    }

    // Send JSON response
    echo json_encode($response);
} 
else {
    // Handle non-POST requests if needed
    echo 'Invalid request method.';
}
?>