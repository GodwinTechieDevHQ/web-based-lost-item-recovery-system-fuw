<?php
// update_profile.php
include("header.php");

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Connect to the database
    include("includes/dbh.inc.php");

    // Check if the connection is successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user_id = $_SESSION['user_id'];

    // Query the database to retrieve the current profile picture file path
    $sql = "SELECT profile_picture, verification_document FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Initialize the profile picture and ID card paths
    $profile_picture_path = '';
    $id_card_path = '';

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $profile_picture_path = 'assets/images/profile_pictures/' . $row['profile_picture'];
        $id_card_path = 'assets/images/id_cards/' . $row['verification_document'];
    }

    // Close the database connection
    $stmt->close();
?>

<div class="update-profile-picture">
    <h1>Account Verification</h1>
    <form id="update-profile-form" action="update_profile.php" method="post" enctype="multipart/form-data">
        <!-- Profile Picture -->
        <label for="profile_image">Choose a new profile picture:</label>
        <input type="file" id="profile-image-input" name="profile_image" accept="image/*">
        <img style="max-width: 300px; max-height: 300px; display: none;" id="profile-image-preview"
            class="profile-pic-preview" src="<?php echo $profile_picture_path; ?>" alt="Current Profile Picture">

        <!-- ID Card -->
        <label for="id_card">Upload a picture of your ID card:</label>
        <input type="file" id="id-card-input" name="id_card" accept="image/*">
        <img style="max-width: 300px; max-height: 300px; display: none;" id="id-card-preview" class="id-card-preview"
            src="<?php echo $id_card_path; ?>" alt="Current ID Card Image">

        <button type="submit" name="submit">Upload</button>
    </form>
</div>

<!-- Add the following script at the end of your HTML body section -->
<script>
function previewImage(input, previewId) {
    var preview = document.getElementById(previewId);
    var files = input.files;

    if (files.length > 0) {
        var reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = "block";
        };

        reader.readAsDataURL(files[0]);
    } else {
        preview.src = "#";
        preview.style.display = "none";
    }
}

// Trigger the image preview when a file is selected
document.getElementById("profile-image-input").addEventListener("change", function() {
    previewImage(this, "profile-image-preview");
});

document.getElementById("id-card-input").addEventListener("change", function() {
    previewImage(this, "id-card-preview");
});

// Confirmation before form submission
// document.getElementById("update-profile-form").addEventListener("submit", function(e) {
//     e.preventDefault();
//     var confirmation = confirm("Are you sure you want to update your profile?");
//     if (confirmation) {
//         this.submit(); // Submit the form
//     }
// });
</script>

<?php
    // Check if the form is submitted
    if (isset($_POST['submit'])) {

        function handleImageUpload($inputName, $targetPath) {
            if ($_FILES[$inputName]["error"] === 0) {
                $image = $_FILES[$inputName]["name"];
                $image_tmp = $_FILES[$inputName]["tmp_name"];
                $targetFilePath = $targetPath.$image;
        
                // Check if the file was successfully moved
                if (move_uploaded_file($image_tmp, $targetFilePath)) {
                    return $image; // Return the image file name
                } else {
                    return "fileupload: Failed to move file"; // Return an error message
                }
            } else {
                return "fileupload: ".$_FILES[$inputName]["error"]; // Return an error message
            }
        }
        
        // Handle image upload and update database
        $image = handleImageUpload("profile_image", "assets/images/profile_pictures/");
        $image1 = handleImageUpload("id_card", "assets/images/id_cards/");
        $pending = 'pending';
        // Update user profile with new image paths
        $updateSql = "UPDATE users SET profile_picture = ?, verification_document = ?, verification_status = ? WHERE user_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ssss", $image, $image1, $pending, $user_id);
                
        if ($updateStmt->execute()) {
            echo '<script>alert("Uploaded successfully.");
            window.location="profile.php";
            </script>';
            // You may redirect the user to another page if needed
        } else {
            echo '<script>alert("Error updating profile: ' . $updateStmt->error . '");</script>';
        }

        // Close the statement
        $updateStmt->close();
    }

    // Close the database connection
    $conn->close();
} else {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
}

include("footer.php");
?>