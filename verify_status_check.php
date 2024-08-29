<?php
// Include the database connection file to establish a connection to the database
include 'includes/dbh.inc.php';

// Start a new session or resume the existing one
session_start();

/**
 * Function to get the verification status of a user from the database
 *
 * @param int $user_id The ID of the user whose verification status we want to check
 * @return string|null The verification status of the user (e.g., 'verified' or 'unverified'), or null if not found
 */
function getVerificationStatus($user_id)
{
    // Access the global database connection variable
    global $conn;

    // SQL query to select the verification status from the users table where the user_id matches
    $sql = "SELECT verification_status FROM users WHERE user_id = ?";
    
    // Initialize a prepared statement to prevent SQL injection attacks
    $stmt = mysqli_stmt_init($conn);

    // Check if the statement was successfully prepared
    if (mysqli_stmt_prepare($stmt, $sql)) {
        // Bind the user_id parameter to the SQL query as an integer ("i")
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        
        // Execute the prepared statement
        mysqli_stmt_execute($stmt);
        
        // Get the result set from the executed statement
        $result = mysqli_stmt_get_result($stmt);

        // Fetch the row from the result set as an associative array
        if ($row = mysqli_fetch_assoc($result)) {
            // Return the verification status from the fetched row
            return $row['verification_status'];
        }
    }
    // If no row is found or there's an error, the function will return null by default
}

// Retrieve the user ID from the session, assuming it was stored there after login
$user_id = $_SESSION['user_id'];

// Call the function to get the user's verification status
$verification_status = getVerificationStatus($user_id);

// Check if the user is unverified
if ($verification_status === 'unverified') {
    // If the user is unverified, display an alert message and redirect them to the profile update page
    echo '<script>
            alert("Your account is not verified. Please verify your account to perform any operations.");
            window.location="update_profile.php";
          </script>';
    // Stop further script execution
    exit();
}
?>
