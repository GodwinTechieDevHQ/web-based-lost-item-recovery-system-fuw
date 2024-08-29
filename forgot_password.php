<?php
// forgot_password.php - Step 1: Capture Email Address

// Include your database connection
include 'includes/dbh.inc.php';

if(isset($_POST['submit'])) {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate a secure token
        $token = bin2hex(random_bytes(16));

        // Store the token and expiration timestamp in the database
        $sql = "UPDATE users SET reset_token = ?, reset_token_expires_at = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$token, $email]);

        // Send Email
        $reset_link = "http://example.com/reset_password.php?token=" . $token;
        $message = "Click the following link to reset your password: $reset_link";
        mail($email, "Password Reset", $message);
        
        echo "An email has been sent with instructions to reset your password.";
    } else {
        echo "Email address not found.";
    }
}
?>

<!-- HTML form to capture email address -->
<form action="forgot_password.php" method="post">
    <input type="email" name="email" placeholder="Enter your email" required>
    <button type="submit" name="submit">Reset Password</button>
</form>