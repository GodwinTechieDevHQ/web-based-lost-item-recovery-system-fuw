<?php
include("header.php");

// Check if the user is logged in
include("../includes/login_check.inc.php");

// Connect to your database (replace with your actual credentials)
include('../includes/dbh.inc.php');

// Fetch messages for the current user
$user_id = $_SESSION['user_id'];
$sql = "SELECT DISTINCT receiver_id, sender_id, MAX(timestamp) AS last_timestamp
        FROM private_messages
        WHERE receiver_id = '$user_id' OR sender_id = '$user_id'
        GROUP BY receiver_id, sender_id
        ORDER BY last_timestamp DESC";
$result = $conn->query($sql);

?>

<div class="container mt-4">
    <h2>Messages</h2>

    <?php
    // Check if there are any messages for the current user
    if ($result->num_rows > 0) {
        // Display the list of users with whom the current user has messages
        echo '<ul class="list-group">';
        $displayed_users = []; // Array to keep track of displayed users
        while ($row = $result->fetch_assoc()) {
            $other_user_id = ($row['receiver_id'] == $user_id) ? $row['sender_id'] : $row['receiver_id'];

            // Fetch the other user's details (replace with your actual user table structure)
            if (!in_array($other_user_id, $displayed_users)) {
                $user_info_sql = "SELECT first_name, last_name, profile_picture FROM users WHERE user_id = '$other_user_id'";
                $user_info_result = $conn->query($user_info_sql);

                if ($user_info_result->num_rows > 0) {
                    $user_info = $user_info_result->fetch_assoc();
                    $other_user_name = $user_info['first_name'] . ' ' . $user_info['last_name'];
                    $other_user_profile_picture = "../assets/images/profile_pictures/" . $user_info['profile_picture'];

                    // Fetch the last message between the current user and the other user
                    $last_message_sql = "SELECT message_text, sender_id FROM private_messages
                                         WHERE (sender_id = '$user_id' AND receiver_id = '$other_user_id')
                                         OR (sender_id = '$other_user_id' AND receiver_id = '$user_id')
                                         ORDER BY timestamp DESC
                                         LIMIT 1";
                    $last_message_result = $conn->query($last_message_sql);

                    if ($last_message_result->num_rows > 0) {
                        $last_message_data = $last_message_result->fetch_assoc();
                        $last_message_text = $last_message_data['message_text'];
                        $is_from_user = ($last_message_data['sender_id'] == $user_id);

                        // Display the user profile picture, name, and last message
                        echo '<li class="list-group-item">';
                        echo '<img class="msg-upic rounded-circle" src="' . $other_user_profile_picture . '" alt="' . $other_user_name . '" style="width: 50px; height: 50px;">';
                        echo '<div class="d-inline-block ml-3">';
                        echo '<p><a class="msg-uname" href="private_chat.php?other_user_id='. $other_user_id . '">' . $other_user_name . '</a>: <br>'; 
                        echo ($is_from_user ? 'You: ' : '') . $last_message_text . '</p>';
                        echo '</div>';
                        echo '</li>';

                        $displayed_users[] = $other_user_id; // Add the user to the displayed list
                    }
                }
            }
        }
        echo '</ul>';
    } else {
        // No messages for the current user
        echo '<p>You do not have any messages yet.</p>';
    }
    ?>
</div>

<?php include("../footer.php"); ?>