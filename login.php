<?php include 'header.php'; ?>

<!-- Modal -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="helpModalLabel">Need Help?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Choose an option:</p>
                <a href="help/help.php" class="btn btn-primary" target="_blank">Tutorials</a>
                <a href="feedback.php" class="btn btn-danger" target="_blank">Feedback</a>
            </div>
        </div>
    </div>
</div>

<div class="form-container">
    <h2>Login</h2>
    <form action="includes/login.inc.php" method="post">
        <input type="text" name="matriculation_number" placeholder="Matriculation Number" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="btn btn-primary">Login</button>
        <a href="forgot_password.php">Forgot Password?</a>
    </form>
    <p>Don't have an account? <a href="signup.php">Signup</a></p>
    <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#helpModal">Need Help?</a>

    <?php 
         if (isset($_GET['error'])) {
            if ($_GET["error"] == "invalidpassword") {
                echo "<div class='alert alert-warning' role='alert' style='color: #ff6666';>Matriculation Number and Password do not match!</div>";
            }
            else if ($_GET["error"] == "usernotfound") {
                echo "<div class='alert alert-warning' role='alert' style='color: #ff6666';>User not found. Please check your matriculation number and try again.</div>";
            }
        }
        if (isset($_GET['notify'])) {
            if ($_GET["notify"] == "ls") {
                echo "<h3>Login Successful!</h3>";
            }
            if ($_GET["notify"] == "lo") {
                echo "<h3>Logout Successful!</h3>";
            }
        }
    ?>
</div>

<?php include 'footer.php'; ?>