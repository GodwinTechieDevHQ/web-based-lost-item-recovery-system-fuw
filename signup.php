<?php include 'header.php'; ?>
<div class="form-container">
    <h2>Signup</h2>
    <form action="includes/signup.inc.php" method="post">
        <input type="text" name="first_name" placeholder="First Name" required
            value="<?= isset($_GET['first_name']) ? htmlspecialchars($_GET['first_name']) : ''; ?>">
        <input type="text" name="middle_name" placeholder="Middle Name (optional)"
            value="<?= isset($_GET['middle_name']) ? htmlspecialchars($_GET['middle_name']) : ''; ?>">
        <input type="text" name="last_name" placeholder="Last Name" required
            value="<?= isset($_GET['last_name']) ? htmlspecialchars($_GET['last_name']) : ''; ?>">
        <input type="email" name="email" type="email" placeholder="Email" required
            value="<?= isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
        <input type="text" name="matriculation_number" placeholder="Matriculation Number" required
            value="<?= isset($_GET['matriculation_number']) ? htmlspecialchars($_GET['matriculation_number']) : ''; ?>">
        <input type="tel" name="phone_number" type="number" placeholder="Phone Number" required
            value="<?= isset($_GET['phone_number']) ? htmlspecialchars($_GET['phone_number']) : ''; ?>">
        <label for="gender">Gender:</label>
        <select name="gender" id="gender" required>
            <option value="male" <?= (isset($_GET['gender']) && $_GET['gender'] === 'male') ? 'selected' : ''; ?>>Male
            </option>
            <option value="female" <?= (isset($_GET['gender']) && $_GET['gender'] === 'female') ? 'selected' : ''; ?>>
                Female</option>
            <option value="other" <?= (isset($_GET['gender']) && $_GET['gender'] === 'other') ? 'selected' : ''; ?>>
                Other</option>
        </select>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <?php
        if (isset($_GET['error'])) {
            $errors = explode(",", $_GET['error']);

            foreach ($errors as $error) {
                switch ($error) {
                    case "passwordmismatch":
                        echo "<div class='alert alert-warning' role='alert' style='color: #ff6666;'>Passwords do not match. Please make sure the passwords match and try again.</div>";
                        break;
                    case "uidExists":
                        echo "<div class='alert alert-warning' role='alert' style='color: #ff6666;'>User with this Matriculation Number already exists. Please check your Matriculation number and try again or Visit the Security Department if You believe you are being impersonated.</div>";
                        break;
                    case "nameRegex":
                        echo "<div class='alert alert-warning' role='alert' style='color: #ff6666;'>Invalid name format. Names should start with a capital letter and contain only letters.</div>";
                        break;
                    case "emailRegex":
                        echo "<div class='alert alert-warning' role='alert' style='color: #ff6666;'>Invalid email format. Please enter a valid email address.</div>";
                        break;
                    case "numberRegex":
                        echo "<div class='alert alert-warning' role='alert' style='color: #ff6666;'>Invalid phone number format. Please enter a valid phone number.</div>";
                        break;
                    case "pwdRegex":
                        echo "<div class='alert alert-warning' role='alert' style='color: #ff6666;'>Invalid password format. Passwords should be at least 8 characters long and include at least one uppercase letter, one lowercase letter, and one digit.</div>";
                        break;
                    default:
                        echo "<div class='alert alert-danger' role='alert' style='color: #ff6666;'>An unknown error occurred.</div>";
                }
            }
        }
        ?>
        <button type="submit">Signup</button>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var errorDiv = document.querySelector('.alert-warning') || document.querySelector('.alert-danger');
    if (errorDiv) {
        var inputWithError = errorDiv.closest('form').querySelector('input[name]');
        if (inputWithError) {
            inputWithError.focus();
        }
        errorDiv.scrollIntoView({
            behavior: 'smooth',
            block: 'center',
            inline: 'center'
        });
    }
});
</script>

<?php include 'footer.php'; ?>