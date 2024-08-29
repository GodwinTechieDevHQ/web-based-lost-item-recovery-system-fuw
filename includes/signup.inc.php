<!-- signup.inc.php -->
<?php
include 'functions.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($conn, $_POST['middle_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $matriculation_number = mysqli_real_escape_string($conn, $_POST['matriculation_number']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Array to store error messages
    $errors = array();

    // Validate name
    if (!validateName($first_name) || !validateName($middle_name) || !validateName($last_name)) {
        $errors[] = "nameRegex";
    }

    // Validate phone number
    if (!validatePhoneNumber($phone_number)) {
        $errors[] = "numberRegex";
    }

    // Validate password
    if (!validatePassword($password)) {
        $errors[] = "pwdRegex";
    }

    // Check if the password and confirm password match
    if ($password !== $confirm_password) {
        $errors[] = "passwordmismatch";
    }

    // Check if the user already exists
    if (userExists($matriculation_number)) {
        $errors[] = "uidExists";
    }

    // If there are errors, redirect with error messages
    if (!empty($errors)) {
        $errorString = implode(",", $errors);
        header("Location: ../signup.php?error=$errorString");
        exit();
    }

    // Call the signupUser function and handle the result
    $signup_result = signupUser($first_name, $middle_name, $last_name, $email, $phone_number, $gender, $matriculation_number, $password, $confirm_password);

    if ($signup_result === "success") {
        header("Location: ../signup.php?signup=success");
        exit();
    } else if ($signup_result === "sqlerror") {
        header("Location: ../signup.php?error=sqlerror");
        exit();
    }
} else {
    header("Location: ../signup.php");
    exit();
}
?>