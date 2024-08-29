<?php
include 'includes/dbh.inc.php';

session_start();

function getVerificationStatus($user_id)
{
    global $conn;

    $sql = "SELECT verification_status FROM users WHERE user_id = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            return $row['verification_status'];
        }
    }
}


$user_id = $_SESSION['user_id']; // Assuming you store user_id in the session after login

$verification_status = getVerificationStatus($user_id);

if ($verification_status === 'unverified') {
    echo '<script>
            alert("Your account is not verified. Please verify your account to perform any operations.");
            window.location="update_profile.php";
          </script>';
    exit();
}
?>