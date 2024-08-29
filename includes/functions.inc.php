<!-- functions.inc.php -->
<?php
include 'dbh.inc.php';
session_start();

// Function to check if the name follows the specified regex
function validateName($name)
{
    $nameRegex = "/^[A-Z][a-z]*$/";

    if ($name !== '' && !preg_match($nameRegex, $name)) {
        // Redirect to signup page with an error message
        header("Location: ../signup.php?error=nameRegex");
        exit();
    }
    return true; // Return true for valid input
}

// Function to check if the phone number follows the specified regex
function validatePhoneNumber($phone_number)
{
    $numberRegex = "/^\d{11}$/";

    if (!preg_match($numberRegex, $phone_number)) {
        // Redirect to signup page with an error message
        header("Location: ../signup.php?error=numberRegex");
        exit();
    }
    return true; // Return true for valid input
}

// Function to check if the password follows the specified regex
function validatePassword($password)
{
    $passwordRegex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@.])[a-zA-Z\d@.]{6,}$/";


    if (!preg_match($passwordRegex, $password)) {
        // Redirect to signup page with an error message
        header("Location: ../signup.php?error=pwdRegex");
        exit();
    }
    return true; // Return true for valid input
}

// Modify the signupUser function to include these validations
function signupUser($first_name, $middle_name, $last_name, $email, $phone_number, $gender, $matriculation_number, $password, $confirm_password)
{
    global $conn;
    $errors = []; // Initialize an array to store errors

    // Validate first name
    if (!validateName($first_name)) {
        $errors[] = "firstnameRegex";
    }

    // Validate middle name if provided
    if ($middle_name !== '' && !validateName($middle_name)) {
        $errors[] = "middlenameRegex";
    }

    // Validate last name
    if (!validateName($last_name)) {
        $errors[] = "lastnameRegex";
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

    // Check if there are any errors before proceeding
    if (!empty($errors)) {
        // Redirect to signup page with error messages
        $errorString = implode('&', $errors);
        header("Location: ../signup.php?error={$errorString}");
        exit();
    }

    // Perform necessary validation and error handling here

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Set the default type to "user"
    $default_user_type = "user";

    $sql = "INSERT INTO users (first_name, middle_name, last_name, email, type, phone_number, gender, matriculation_number, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return "sqlerror";
    } else {
        mysqli_stmt_bind_param($stmt, "sssssssss", $first_name, $middle_name, $last_name, $email, $default_user_type, $phone_number, $gender, $matriculation_number, $hashed_password);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo '<script>alert("Signup successful! You can now log in."); window.location="../login.php";</script>';
        exit();
    }
}

// Rest of the code...

// New function to check if a user with the given matriculation number already exists
function userExists($matriculation_number)
{
    global $conn;

    $sql = "SELECT user_id FROM users WHERE matriculation_number=?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return false; // Consider it as non-existing if there's a SQL error
    } else {
        mysqli_stmt_bind_param($stmt, "s", $matriculation_number);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $num_rows = mysqli_stmt_num_rows($stmt);
        mysqli_stmt_close($stmt);

        return $num_rows > 0;
    }
}

// functions.inc.php

function loginUser($matriculation_number, $password, $user_type = 'user')
{
    global $conn;

    // Perform necessary validation and error handling here

    $sql = "SELECT * FROM users WHERE matriculation_number=? AND type=?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return "sqlerror";
    } else {
        mysqli_stmt_bind_param($stmt, "ss", $matriculation_number, $user_type);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $password_check = password_verify($password, $row['password']);
            if ($password_check) {
                // Login successful
                session_start();
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['matriculation_number'] = $row['matriculation_number'];
                $_SESSION['first_name'] = $row['first_name'];
                $_SESSION['middle_name'] = $row['middle_name'];
                $_SESSION['last_name'] = $row['last_name'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['profile_picture'] = $row['profile_picture'];
                $_SESSION['user_type'] = $user_type;
                mysqli_stmt_close($stmt);
                return "success";
            } else {
                mysqli_stmt_close($stmt);
                // return "invalidpassword";
                header("Location: ../login.php?error=invalidpassword");
                exit();
            }
        } else {
            mysqli_stmt_close($stmt);
            return "{$user_type}notfound";
        }
    }
}

function report_item($item_name, $description, $location, $lost_or_found, $category, $image, $user_id, $report_type)
{
    global $conn;

    // Fetch user type from the database based on user_id
    $user_type = getUserType($user_id);

    // Check if the report type is for security
    if ($report_type === 'security') {
        // If the report is for security, set the status to "found"
        $status = 'found';
    } else {
        // Otherwise, use the value of $lost_or_found as status
        $status = $lost_or_found;
    }

    $sql = "INSERT INTO lost_items (item_name, item_description, location, status, category_id, item_image, owner_id, report_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return "sqlerror: " . mysqli_error($conn);
    } else {
        mysqli_stmt_bind_param($stmt, "ssssssss", $item_name, $description, $location, $status, $category, $image, $user_id, $report_type);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);

            // Check if the user type is admin
            if ($user_type === 'admin') {
                // Redirect admin to the admin page
                echo '<script>alert("You have successfully reported an item."); 
                window.location="../admin/lost_and_found.php";
                </script>';
                exit();
            } else {
                // Redirect regular user to the regular page
                echo '<script>alert("You have successfully reported an item."); 
                window.location="../lost_and_found.php";
                </script>';
                exit();
            }
        } else {
            return "executeerror: " . mysqli_stmt_error($stmt);
        }
    }
}

// Function to get user type from the database based on user_id
function getUserType($user_id)
{
    global $conn;

    $sql = "SELECT type FROM users WHERE user_id = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            return $row['type'];
        }
    }

    return null; // Return null if user type is not found
}

function update_profile($image, $image1)
{
    global $conn;

    $user_id = $_SESSION['user_id'];

    $sql = "UPDATE users SET profile_picture = ?, verification_document = ? WHERE user_id = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return "sqlerror: " . mysqli_error($conn);
    } else {
        mysqli_stmt_bind_param($stmt, "sss", $image, $image1, $user_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            echo '<script>alert("You have successfully updated Your profile."); 
            window.location="../profile.php";
            </script>';
            exit();
        } else {
            return "executeerror: " . mysqli_stmt_error($stmt);
        }
    }
}

function submit_feedback($name, $email, $feedback_type, $message, $imagePaths) {
    include 'dbh.inc.php';

// Check if $imagePaths is not empty before imploding
if (!empty($imagePaths)) {
    // Convert array of image paths to a comma-separated string
    $imagePathsString = implode(",", $imagePaths);
} else {
    // If $imagePaths is empty, set $imagePathsString to an empty string
    $imagePathsString = '';
}

// Prepare the SQL statement to insert feedback into the database
$sql = "INSERT INTO feedback (name, email, feedback_type, message, image_paths) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $name, $email, $feedback_type, $message, $imagePathsString);

    // Execute the SQL statement
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return "success";
    } else {
        $stmt->close();
        $conn->close();
        return "sqlerror";
    }
}