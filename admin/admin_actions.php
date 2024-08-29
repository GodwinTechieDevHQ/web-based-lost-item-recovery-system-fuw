<?php
include("../includes/dbh.inc.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['action'] == 'reject_user' && isset($_POST['user_id'])) {
        $userId = $_POST['user_id'];

        // Update user status to "rejected"
        $updateSql = "UPDATE users SET verification_status = 'rejected' WHERE user_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("s", $userId);

        if ($updateStmt->execute()) {
            // Delete profile picture and ID card
            $deleteSql = "UPDATE users SET profile_picture = NULL, verification_document = NULL WHERE user_id = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("s", $userId);

            if ($deleteStmt->execute()) {
                echo "User rejected successfully. Profile picture and ID card deleted.";
            } else {
                echo "Error deleting profile picture and ID card.";
            }

            $deleteStmt->close();
        } else {
            echo "Error rejecting user.";
        }

        $updateStmt->close();
    }
}

$conn->close();
?>