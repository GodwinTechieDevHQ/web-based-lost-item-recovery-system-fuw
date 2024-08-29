<?php
// Include database connection file
include("../includes/dbh.inc.php");

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the action is 'reject_user' and 'user_id' is set
    if ($_POST['action'] == 'reject_user' && isset($_POST['user_id'])) {
        $userId = $_POST['user_id']; // Retrieve the user ID from POST data

        // Prepare SQL query to update user status to "rejected"
        $updateSql = "UPDATE users SET verification_status = 'rejected' WHERE user_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("s", $userId); // Bind the user ID parameter

        // Execute the query and check if it was successful
        if ($updateStmt->execute()) {
            // Prepare SQL query to delete the profile picture and ID card
            $deleteSql = "UPDATE users SET profile_picture = NULL, verification_document = NULL WHERE user_id = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("s", $userId); // Bind the user ID parameter

            // Execute the query and check if it was successful
            if ($deleteStmt->execute()) {
                echo "User rejected successfully. Profile picture and ID card deleted.";
            } else {
                echo "Error deleting profile picture and ID card.";
            }

            $deleteStmt->close(); // Close the statement
        } else {
            echo "Error rejecting user.";
        }

        $updateStmt->close(); // Close the statement
    }
}

$conn->close(); // Close the database connection
?>
