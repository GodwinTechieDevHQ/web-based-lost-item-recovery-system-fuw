<?php
// profile.php
include("header.php");

if (isset($_SESSION['user_id'])) {
    // Connect to your database (replace these with your actual database credentials)
    include("../includes/dbh.inc.php");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $user_id = $_SESSION['user_id'];

    // Query the database to retrieve the profile picture file path
    $sql = "SELECT profile_picture FROM users WHERE user_id = '$user_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $dir = "../assets/images/profile_pictures/";
        $profile_picture_path = $dir . $row['profile_picture'];
    } 

    // Query the database to retrieve items in the user's possession
    $itemSql = "SELECT item_name, item_image FROM lost_items WHERE owner_id = '$user_id'";
    $itemResult = $conn->query($itemSql);

    // Close the database connection
    $conn->close();
    ?>
<div class="user-profile">
    <?php if (empty($profile_picture_path)) { ?>
    <div class="update-profile-link">
        <img class="profile_pic" src="../assets/images/profile_pictures/profile.png" alt="Default Profile Picture">
        <a href="update_profile_picture.php">Update Profile Picture</a>
    </div>
    <?php } else { ?>
    <img class="profile_pic" src="<?php echo $profile_picture_path; ?>" alt="Profile Picture">
    <?php } ?>
    <h2><?php echo 'Name: ' . $_SESSION['first_name'] . ' ' . $_SESSION['middle_name'] . ' ' . $_SESSION['last_name'] ?>
    </h2>
    <p><?php echo 'Email: ' . $_SESSION['email'] ?></p>

    <h3>Items in Your Possession</h3>
    <div class="user-items" style="overflow-x: auto; white-space: nowrap;">
        <?php if ($itemResult->num_rows > 0) { ?>
        <ul>
            <?php while ($itemRow = $itemResult->fetch_assoc()) { ?>
            <li>
                <img src="<?php echo '../assets/images/items/' . $itemRow['item_image']; ?>" alt="Item Image">
                <p><?php echo $itemRow['item_name']; ?></p>
            </li>
            <?php } ?>
        </ul>
        <?php } else { ?>
        <p>No items in your possession.</p>
        <?php } ?>
    </div>

    <style>
    /* CSS for centering the table */
    .center-table {
        margin: auto;
    }
    </style>

</div>
<!-- Add the following script at the end of your HTML body section -->
<script src="jquery.js"></script>
<script>
// Removed the transactions-related script
</script>

<?php
} else {
    include("../includes/login_check.inc.php");
}

include("../footer.php"); ?>