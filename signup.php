<?php include 'header.php'; ?>
<div class="form-container">
    <h2>Signup</h2>
    <form action="includes/signup.inc.php" method="post">
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" placeholder="First Name" required
            value="<?= isset($_GET['first_name']) ? htmlspecialchars($_GET['first_name']) : ''; ?>">

        <label for="middle_name">Middle Name (optional)</label>
        <input type="text" id="middle_name" name="middle_name" placeholder="Middle Name (optional)"
            value="<?= isset($_GET['middle_name']) ? htmlspecialchars($_GET['middle_name']) : ''; ?>">

        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" placeholder="Last Name" required
            value="<?= isset($_GET['last_name']) ? htmlspecialchars($_GET['last_name']) : ''; ?>">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Email" required
            value="<?= isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">

        <label for="matriculation_number">Matriculation Number</label>
        <input type="text" id="matriculation_number" name="matriculation_number" placeholder="Matriculation Number"
            required value="<?= isset($_GET['matriculation_number']) ? htmlspecialchars($_GET['matriculation_number']) : ''; ?>">

        <label for="phone_number">Phone Number</label>
        <input type="tel" id="phone_number" name="phone_number" placeholder="Phone Number" required
            value="<?= isset($_GET['phone_number']) ? htmlspecialchars($_GET['phone_number']) : ''; ?>">

        <label for="gender">Gender:</label>
        <select name="gender" id="gender" required>
            <option value="male" <?= (isset($_GET['gender']) && $_GET['gender'] === 'male') ? 'selected' : ''; ?>>Male</option>
            <option value="female" <?= (isset($_GET['gender']) && $_GET['gender'] === 'female') ? 'selected' : ''; ?>>Female</option>
            <option value="other" <?= (isset($_GET['gender']) && $_GET['gender'] === 'other') ? 'selected' : ''; ?>>Other</option>
        </select>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Password" required autocomplete="off">

        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required autocomplete="off">

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
