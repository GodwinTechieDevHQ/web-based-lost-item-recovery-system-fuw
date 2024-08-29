<?php
include("header.php");
include("includes/login_check.inc.php");
include('includes/dbh.inc.php');

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
    return null; // Return null if no result
}

$user_id = $_SESSION['user_id']; // Assuming you store user_id in the session after login
$verification_status = getVerificationStatus($user_id);

if ($verification_status === 'unverified') {
    echo '<script>
            alert("Your account is not verified. Please verify your account to be able to see user profiles.");
            window.location="update_profile.php";
          </script>';
    exit();
}

echo '<a class="btn btn-dark" href="javascript:history.back()" style="float:left">Back</a> <br>';

if (isset($_GET['other_user_id'])) {
    $other_user_id = $_GET['other_user_id'];

    // Use prepared statements for security
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $other_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_info = $result->fetch_assoc();

        // Display user profile information
        echo '<div class="user-profile">';
        $profile_pic = htmlspecialchars($user_info['profile_picture']);
        $first_name = htmlspecialchars($user_info['first_name']);
        $last_name = htmlspecialchars($user_info['last_name']);
        $middle_name = htmlspecialchars($user_info['middle_name']);
        $email = htmlspecialchars($user_info['email']);

        echo '<img class="profile_pic" src="' . "assets/images/profile_pictures/" . $profile_pic . '" alt="' . $first_name . ' ' . $last_name . '">';
        echo '<h2>' . $first_name . ' ' . $last_name . ' ' . $middle_name . '</h2>';
        echo '<p>Email: <a href="mailto:' . $email . '">' . $email . '</a></p>';
        echo '<a href="private_chat.php?other_user_id=' . $other_user_id . '" class="alert-link bg-primary text-white" style="padding:10px;text-decoration:none;">Message</a>';
        echo '</div>';
    } else {
        echo '<p>User not found.</p>';
    }

    $stmt->close();
} else {
    echo '<p>Invalid user ID.</p>';
}

$conn->close();
?>

<?php include("footer.php"); ?>
