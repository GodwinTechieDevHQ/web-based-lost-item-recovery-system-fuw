<?php
include("header.php");
include("../includes/login_check.inc.php");
include('../includes/dbh.inc.php');

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
        echo '<img class="profile_pic" src="' . "../assets/images/profile_pictures/" . $user_info['profile_picture'] . '" alt="' . $user_info['first_name'] . ' ' . $user_info['last_name'] . '">';
        echo '<h2>' . $user_info['first_name'] . ' ' . $user_info['last_name'] . ' ' . $user_info['middle_name'] . '</h2>';
        echo '<p>Email: <a href="mailto:' . $user_info['email'] . '">' . $user_info['email'] . '</a></p>';
        // <!-- Add more user profile details as needed -->
        echo '</div>';
    } else {
        echo '<p>User not found.</p>';
    }
} else {
    echo '<p>Invalid user ID.</p>';
}
?>

<?php include("../footer.php"); ?>