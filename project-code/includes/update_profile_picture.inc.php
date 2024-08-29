<!-- update_profile_picture.inc.php -->
<?php
include 'functions.inc.php';
include 'dbh.inc.php';
session_start();

// rest of the code


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Handle image upload
    $image = handleImageUpload();
    $image1 = handleImageUpload1();

    // Call the update_profile function and handle the result
    $update_result = update_profile($image, $image1);

    if ($update_result === "success") {
        // header("Location: update_profile_picture.php?success");
        header("Location: profile.php");
        exit();
    } elseif ($update_result === "sqlerror") {
        header("Location: update_profile_picture.php?error=sqlerror");
        exit();
    } 
    // elseif ($update_result === "fileupload") {
    //     header("Location: update_profile_picture.php?error=fileupload");
    //     exit();
    // }
} else {
    header("Location: update_profile_picture.php");
    exit();
}

function handleImageUpload() {
    if ($_FILES["profile_picture"]["error"] === 0) {
        $image = $_FILES["profile_picture"]["name"];
        $image_tmp = $_FILES["profile_picture"]["tmp_name"];

        // Check if the file was successfully moved
        if (move_uploaded_file($image_tmp, "asssets/images/profile_pictures/$image")) {
            return $image;
        }
    }
}

function handleImageUpload1() {
    if ($_FILES["id_card"]["error"] === 0) {
        $image1 = $_FILES["id_card"]["name"];
        $image_tmp1 = $_FILES["id_card"]["tmp_name"];

        // Check if the file was successfully moved
        if (move_uploaded_file($image_tmp1, "asssets/images/id_cards/$image1")) {
            return $image1;
        }
    }
}

?>