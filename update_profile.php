<?php
// Include the header.php file, which may contain HTML head elements, navigation, etc.
include("header.php");

// Check if the user is logged in by verifying if 'user_id' is set in the session
if (isset($_SESSION['user_id'])) {
    // Include the database connection file to establish a connection to the database
    include("includes/dbh.inc.php");

    // Check if the database connection was successful
    if ($conn->connect_error) {
        // If the connection failed, terminate the script and display an error message
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the user ID from the session
    $user_id = $_SESSION['user_id'];

    // SQL query to select the current profile picture and verification document paths for the logged-in user
    $sql = "SELECT profile_picture, verification_document FROM users WHERE user_id = ?";
    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare($sql);
    // Bind the user ID to the prepared statement as a string ("s")
    $stmt->bind_param("s", $user_id);
    // Execute the prepared statement
    $stmt->execute();
    // Get the result set from the executed statement
    $result = $stmt->get_result();

    // Initialize variables to store the file paths of the profile picture and ID card
    $profile_picture_path = '';
    $id_card_path = '';

    // Check if a result was returned from the query
    if ($result->num_rows > 0) {
        // Fetch the result row as an associative array
        $row = $result->fetch_assoc();
        // Set the profile picture path by concatenating the directory and the filename
        $profile_picture_path = 'assets/images/profile_pictures/' . $row['profile_picture'];
        // Set the ID card path by concatenating the directory and the filename
        $id_card_path = 'assets/images/id_cards/' . $row['verification_document'];
    }

    // Close the statement to free up resources
    $stmt->close();
?>

<!-- HTML for the update profile picture form -->
<div class="update-profile-picture">
    <h1>Update Profile</h1>
    <form id="update-profile-form" action="update_profile.php" method="post" enctype="multipart/form-data">
        <!-- Input for selecting a new profile picture -->
        <label for="profile_image">Choose a new profile picture:</label>
        <input type="file" id="profile-image-input" name="profile_image" accept="image/*">
        <!-- Preview of the current or newly selected profile picture -->
        <img style="max-width: 300px; max-height: 300px; display: none;" id="profile-image-preview"
            class="profile-pic-preview" src="<?php echo $profile_picture_path; ?>" alt="Current Profile Picture">

        <!-- Input for uploading a picture of an ID card -->
        <label for="id_card">Upload a picture of your ID card:</label>
        <input type="file" id="id-card-input" name="id_card" accept="image/*">
        <!-- Preview of the current or newly selected ID card -->
        <img style="max-width: 300px; max-height: 300px; display: none;" id="id-card-preview" class="id-card-preview"
            src="<?php echo $id_card_path; ?>" alt="Current ID Card Image">

        <!-- Submit button to update the profile -->
        <button type="submit" name="submit">Update Profile</button>
    </form>
</div>

<!-- JavaScript to handle image previews -->
<script>
// Function to preview the selected image before upload
function previewImage(input, previewId) {
    var preview = document.getElementById(previewId); // Get the preview image element by ID
    var files = input.files; // Get the files selected in the input

    // Check if there are any files selected
    if (files.length > 0) {
        var reader = new FileReader(); // Create a FileReader object to read the file

        // When the file is read, set the preview image's source to the file data
        reader.onload = function(e) {
            preview.src = e.target.result; // Set the preview image's source to the file data
            preview.style.display = "block"; // Display the preview image
        };

        // Read the file as a data URL
        reader.readAsDataURL(files[0]);
    } else {
        // If no file is selected, hide the preview image
        preview.src = "#";
        preview.style.display = "none";
    }
}

// Add event listeners to trigger the image preview when a file is selected
document.getElementById("profile-image-input").addEventListener("change", function() {
    previewImage(this, "profile-image-preview");
});

document.getElementById("id-card-input").addEventListener("change", function() {
    previewImage(this, "id-card-preview");
});

// Uncomment this section if you want to add a confirmation prompt before form submission
// document.getElementById("update-profile-form").addEventListener("submit", function(e) {
//     e.preventDefault(); // Prevent the default form submission
//     var confirmation = confirm("Are you sure you want to update your profile?");
//     if (confirmation) {
//         this.submit(); // Submit the form if the user confirms
//     }
// });
</script>

<?php
    // Check if the form was submitted
    if (isset($_POST['submit'])) {

        // Function to handle the image upload process
        function handleImageUpload($inputName, $targetPath) {
            // Check if there was no error during the file upload
            if ($_FILES[$inputName]["error"] === 0) {
                // Get the uploaded file's name and temporary location
                $image = $_FILES[$inputName]["name"];
                $image_tmp = $_FILES[$inputName]["tmp_name"];
                // Set the target file path where the image will be saved
                $targetFilePath = $targetPath . $image;
        
                // Try to move the uploaded file to the target directory
                if (move_uploaded_file($image_tmp, $targetFilePath)) {
                    return $image; // Return the image file name if successful
                } else {
                    return "fileupload: Failed to move file"; // Return an error message if the move fails
                }
            } else {
                return "fileupload: " . $_FILES[$inputName]["error"]; // Return an error message if there was an upload error
            }
        }
        
        // Handle the profile picture and ID card image uploads and get their file names
        $image = handleImageUpload("profile_image", "assets/images/profile_pictures/");
        $image1 = handleImageUpload("id_card", "assets/images/id_cards/");
        $pending = 'pending'; // Set the verification status to pending

        // SQL query to update the user's profile picture, ID card, and verification status in the database
        $updateSql = "UPDATE users SET profile_picture = ?, verification_document = ?, verification_status = ? WHERE user_id = ?";
        // Prepare the SQL statement
        $updateStmt = $conn->prepare($updateSql);
        // Bind the new image paths and status to the prepared statement
        $updateStmt->bind_param("ssss", $image, $image1, $pending, $user_id);
                
        // Execute the update statement
        if ($updateStmt->execute()) {
            // If the update was successful, display a success message and redirect to the profile page
            echo '<script>alert("Profile updated successfully.");
            window.location="profile.php";
            </script>';
        } else {
            // If there was an error, display an error message
            echo '<script>alert("Error updating profile: ' . $updateStmt->error . '");</script>';
        }

        // Close the update statement to free up resources
        $updateStmt->close();
    }

    // Close the database connection
    $conn->close();
} else {
    // If the user is not logged in, redirect them to the login page
    header("Location: login.php");
    exit();
}

// Include the footer.php file, which may contain closing HTML tags, footer content, etc.
include("footer.php");
?>
