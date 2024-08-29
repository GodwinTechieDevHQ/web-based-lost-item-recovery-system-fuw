<?php
include 'functions.inc.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matriculation_number = mysqli_real_escape_string($conn, $_POST['matriculation_number']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Call the loginUser function for regular user login
    $login_result = loginUser($matriculation_number, $password);

    // Call the loginUser function for admin login
    $admin_login_result = loginUser($matriculation_number, $password, 'admin');

    if ($admin_login_result === "success") {
        header("Location: ../admin/admin_dashboard.php");
        exit();
    } elseif ($login_result === "success") {
        header("Location: ../home.php");
        exit();
    } elseif ($login_result === "invalidpassword") {
        header("Location: login.php?error=invalidpassword");
        exit();
    } elseif ($login_result === "usernotfound") {
        header("Location: ../login.php?error=usernotfound");
        exit();
    } elseif ($login_result === "sqlerror") {
        header("Location: ../login.php?error=sqlerror");
        exit();
    }
} else {
    echo '<script> alert("You Cannot Login!");
    window.location = "login.php" ;
    </script>';
    exit();
}
?>