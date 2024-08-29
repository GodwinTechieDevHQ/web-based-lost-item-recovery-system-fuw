<?php include 'header.php'; ?>

<div class="form-container">
    <h2>Login</h2>
    <form action="includes/login.inc.php" method="post">
        <input type="text" name="matriculation_number" placeholder="Matriculation Number" required aria-label="Matriculation Number">
        <input type="password" name="password" placeholder="Password" required aria-label="Password">
        <!-- CSRF Token for security -->
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="signup.php">Signup</a></p>
    
    <?php 
        if (isset($_GET['error'])) {
            switch ($_GET["error"]) {
                case "invalidpassword":
                    echo "<div class='alert alert-warning' role='alert' style='color: #ff6666;'>Matriculation Number and Password do not match!</div>";
                    break;
                case "usernotfound":
                    echo "<div class='alert alert-warning' role='alert' style='color: #ff6666;'>User not found. Please check your matriculation number and try again.</div>";
                    break;
            }
        }

        if (isset($_GET['notify'])) {
            switch ($_GET["notify"]) {
                case "ls":
                    echo "<h3>Login Successful!</h3>";
                    break;
                case "lo":
                    echo "<h3>Logout Successful!</h3>";
                    break;
            }
        }
    ?>
</div>

<?php include 'footer.php'; ?>
