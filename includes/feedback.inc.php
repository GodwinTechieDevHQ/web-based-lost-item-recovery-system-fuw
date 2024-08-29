<!-- includes/feedback.inc.php -->
<?php
// includes/feedback.inc.php
include 'functions.inc.php';
include 'dbh.inc.php'; // Ensure this file includes the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form fields
    $name = $_POST['name'];
    $email = $_POST['email'];
    $feedback_type = $_POST['feedback-type'];
    $message = $_POST['message'];

    // Handle image upload
// Call the handleImageUploads function with the name of the file input field
$imagePaths = handleImageUploads("images");

    // Call the submit_feedback function and handle the result
    $update_result = submit_feedback($name, $email, $feedback_type, $message, $imagePaths);

    if ($update_result === "success") {
        header("Location: ../feedback.php?success");
        exit();
    } elseif ($update_result === "sqlerror") {
        header("Location: ../feedback.php?error=sqlerror");
        exit();
    } 
} else {
    header("Location: ../feedback.php");
    exit();
}

function handleImageUploads($inputName) {
    $imagePath = '';

    if ($_FILES[$inputName]["error"] === 0) {
        $image = $_FILES[$inputName]["name"];
        $image_tmp = $_FILES[$inputName]["tmp_name"];
        $uploadDir = 'assets/images/feedback/';

        // Generate a unique filename to avoid overwriting existing files
        $imagePath = $uploadDir . '_' . $image;

        // Check if the file was successfully moved
        if (move_uploaded_file($image_tmp, $imagePath)) {
            return $imagePath;
        } else {
            // Handle upload failure
        }
    }

    return $imagePath;
}

?>