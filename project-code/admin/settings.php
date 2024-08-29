<?php
include("header.php"); // Include your header

// Check if the user is logged in and has the necessary permissions to access settings
// Insert your session and permission checks here

?>

<div class="container mt-4">

    <h2>System Settings</h2>

    <!-- User Account Settings -->
    <div class="card mt-4">
        <div class="card-header">
            User Account Settings
        </div>
        <div class="card-body">
            <!-- Password change form -->
            <form action="change_password.php" method="post">
                <!-- Add input fields for old password, new password, confirm password -->
                <!-- Add submit button -->
            </form>

            <!-- Two-factor authentication settings form -->
            <form action="two_factor_auth.php" method="post">
                <!-- Add input fields and options for two-factor authentication -->
                <!-- Add submit button -->
            </form>
        </div>
    </div>

    <!-- System Security -->
    <!-- Add similar card structures for other sections -->

</div>

<?php include("footer.php"); // Include your footer ?>