<!-- logout.inc.php in a folder called includes -->
<?php
session_start();
session_destroy();

// Redirect to the login page or any other desired page
header("Location: ../login.php");
