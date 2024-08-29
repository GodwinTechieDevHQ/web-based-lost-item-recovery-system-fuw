<?php
// update_profile_picture.php
include("header.php");

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Connect to the database
    include("includes/dbh.inc.php");

    // Check if the connection is successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user_id = $_SESSION['user_id'];

    // Query the database to retrieve the current profile picture file path
    $sql = "SELECT profile_picture FROM users WHERE user_id = '$user_id'";
    $result = $conn->query($sql);

    // Initialize the profile picture path
    $profile_picture_path = '';

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $profile_picture_path = $row['profile_picture'];
    }

    // Close the database connection
    $conn->close();
?>

<div class="update-profile-picture">
    <h1>Update Profile Picture</h1>
    <form action="includes/update_profile_picture.inc.php" method="post" enctype="multipart/form-data">
        <label for="profile_image">Choose a new profile picture:</label>
        <input type="file" name="profile_image" accept="image/*">
        <img style="max-width: 300px; max-height: 300px; display: none;" id="item-image-preview" class="profile-pic"
            src="<?php echo $profile_picture_path; ?> " alt="Current Profile Picture">
        <button type="submit" name="submit">Upload</button>
    </form>
    <?php // Check for file upload error
 if (isset($_GET['error'])) {
    if ($_GET["error"] == "el") {
        echo "<h4>Fill in all fields!</h4>";
    }
    if ($_GET["error"] == "lu") {
        echo "<h4>Login Unsuccessful!</h4>";
    }
}
if (isset($_GET['notify'])) {
    if ($_GET["notify"] == "ls") {
        echo "<h3>Login Successful!</h3>";
    }
    if ($_GET["notify"] == "lo") {
        echo "<h3>Logout Successful!</h3>";
    }
}  ?>
</div>

<?php
} else {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
}

include("footer.php");
?>