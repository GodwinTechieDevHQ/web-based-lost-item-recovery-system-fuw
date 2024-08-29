<?php
// profile.php
include("header.php");

if (isset($_SESSION['user_id'])) {
    // Connect to your database (replace these with your actual database credentials)
    include("includes/dbh.inc.php");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user_id = $_SESSION['user_id'];

    // Query the database to retrieve the profile picture file path and verification status
    $sql = "SELECT profile_picture, verification_status FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $profile_picture_path = 'assets/images/profile_pictures/' . $row['profile_picture'];
        $verification_status = $row['verification_status'];
    } 

    // Query the database to retrieve items in the user's possession
    $itemSql = "SELECT item_name, item_image FROM lost_items WHERE owner_id = ?";
    $itemStmt = $conn->prepare($itemSql);
    $itemStmt->bind_param("s", $user_id);
    $itemStmt->execute();
    $itemResult = $itemStmt->get_result();

    // Close the database connection
    $stmt->close();
    $conn->close();
    ?>

<div class="user-profile">
    <?php
    if ($verification_status === 'unverified') {
        // User has no profile picture and is unverified
        echo '<div class="verification-message" style="background-color: #ff6666; padding: 10px; border-radius: 5px; text-align: center; font-size: 18px;">Please upload a picture of your face and a picture of your ID card for verification. ';
        echo '<a href="update_profile.php" class="alert-link">Update Profile</a></div>';
    } elseif ($verification_status === 'pending') {
        // User has no profile picture but is pending verification
        echo '<div class="verification-message" style="background-color: #ff6666; padding: 10px; border-radius: 5px; text-align: center; font-size: 18px;">Your account verification is pending. Please be patient; the account verification may take a little while. You can come back later.</div>';
    } elseif ($verification_status === 'verified') {
        // User is verified and has a profile picture
        echo '<img class="profile_pic" style="width:20%;" src="' . $profile_picture_path . '" alt="Profile Picture">';
    } elseif ($verification_status === 'rejected') {
        // User verification has been rejected
        echo '<div class="verification-message" style="background-color: #ff6666; padding: 10px; border-radius: 5px; text-align: center; font-size: 18px;">Your account verification has been rejected, Please retry the verification process! ';
        echo '<a href="update_profile.php" class="alert-link">Update Profile</a></div>';
    }
    
    ?>

    <h2>
        <?php echo 'Name: ' . $_SESSION['first_name'] . ' ' . $_SESSION['middle_name'] . ' ' . $_SESSION['last_name'] ?>
    </h2>
    <p><?php echo 'Email: ' . $_SESSION['email'] ?></p>
    <?php
    // Display items in possession only if $itemResult is set and not null
    if (isset($itemResult) && $itemResult !== null) {
        if ($itemResult->num_rows > 0) {
            ?>
    <h3>Items in Your Possession</h3>
    <div class="user-items" style="overflow-x: auto; white-space: nowrap;">
        <ul>
            <?php while ($itemRow = $itemResult->fetch_assoc()) { ?>
            <li>
                <?php 
                if ($itemRow["item_image"] !== "fileupload: 4") {
                ?>
                <img style="width:25%;" src="<?php echo 'assets/images/items/' . $itemRow['item_image']; ?>"
                    alt="Item Image">
                <?php }
                    else {
                    echo "<h5>No image For this item!</h5>";
                    }
                    ?>
                <p><?php echo $itemRow['item_name']; ?></p>
            </li>
            <?php } ?>
        </ul>
    </div>
    <?php } else { ?>
    <p>No items in your possession.</p>
    <?php }
    } else {
        // Handle the case where $itemResult is not set or null
        echo "<p>Error retrieving items.</p>";
    }
    ?>

</div>
<!-- Add the following script at the end of your HTML body section -->
<script src="jquery.js"></script>
<script>
// Removed the transactions-related script
</script>

<?php
} else {
    include("includes/login_check.inc.php");
}

include("footer.php");
?>