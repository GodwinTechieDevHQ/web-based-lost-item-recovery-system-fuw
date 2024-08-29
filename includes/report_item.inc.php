<?php
// report_item.inc.php
include 'functions.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $lost_or_found = mysqli_real_escape_string($conn, $_POST['lost_or_found']);
    $report_type = mysqli_real_escape_string($conn, $_POST['report_type']); // Added report_type
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $user_id = $_SESSION['user_id'];

    // Handle image upload
    $image = handleImageUpload();

    // Determine the status based on the report type
    if ($report_type === 'security') {
        $status = 'found';
    } else {
        $status = $lost_or_found;
    }

    // Call the report_item function and handle the result
    $report_result = report_item($item_name, $description, $location, $lost_or_found, $category, $image, $user_id, $report_type); // Pass status

    if ($report_result === "success") {
        header("Location: report.php?success");
        exit();
    } else if ($report_result === "sqlerror") {
        header("Location: report.php?error=sqlerror");
        exit();
    } else {
        header("Location: report.php");
        exit();
    }
} else {
    header("Location: report.php");
    exit();
}

// Function to handle image upload
function handleImageUpload() {
    if ($_FILES["item_image"]["error"] === 0) {
        $image = $_FILES["item_image"]["name"];
        $image_tmp = $_FILES["item_image"]["tmp_name"];

        // Check if the file was successfully moved
        if (move_uploaded_file($image_tmp, "../assets/images/items/$image")) {
            return $image;
        } else {
            return "fileupload: " . $_FILES["item_image"]["error"]; // Return the specific error message
        }
    } else {
        return "fileupload: ".$_FILES["item_image"]["error"]; // Return an error message
    }
}
?>