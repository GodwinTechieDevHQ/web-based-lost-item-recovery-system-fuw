<?php

if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page if not logged in
    echo "<script>alert('You are not logged in!');
    window.location = 'login.php' ;
    </script>";
    // header("Location: login.php");
    exit();
}
