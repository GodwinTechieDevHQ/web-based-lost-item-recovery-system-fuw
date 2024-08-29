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
    $user_id = $_SESSION["user_id"];

    // $other_user_id = $_GET['user_id'];
    $other_user_id = isset($_GET['other_user_id']) ? $_GET['other_user_id'] : null;


    // Query the database to retrieve the user's information
    $user_query = "SELECT * FROM users WHERE user_id = '$other_user_id'";
    $user_result = $conn->query($user_query);

    if ($user_result->num_rows > 0) {
        $user_info = $user_result->fetch_assoc();

        // Display user profile information using the same structure as profile.php
        echo '<div class="user-profile">';
        echo '<img class="profile_pic" src="' . "assets/images/profile_pictures/" . $user_info['profile_picture'] . '" alt="' . $user_info['first_name'] . ' ' . $user_info['last_name'] . '">';
        echo '<h2>' . $user_info['first_name'] . ' ' . $user_info['last_name'] . ' ' . $user_info['middle_name'] . '</h2>';
        echo '<p>Email: <a href="mailto:' . $user_info['email'] . '">' . $user_info['email'] . '</a></p>';
        echo '<a href="private_chat.php?other_user_id=' . $other_user_id . '" class="alert-link bg-primary text-white" style="padding:10px;text-decoration:none;">Message</a>';

        // <!-- Add more user profile details as needed -->
        echo '</div>';
    } else {
        echo '<p>User not found.</p>';
    }
} else {
    echo '<p>Invalid user ID.</p>';
}
?>

<?php include("footer.php"); ?>