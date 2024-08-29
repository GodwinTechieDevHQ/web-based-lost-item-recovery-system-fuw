<?php
include("header.php");
include("includes/login_check.inc.php");
include("includes/dbh.inc.php");

function getVerificationStatus($user_id)
{
    global $conn;
    $sql = "SELECT verification_status FROM users WHERE user_id = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            return $row['verification_status'];
        }
    }
    return null; // Return null if no result
}

$user_id = $_SESSION['user_id']; // Assuming you store user_id in the session after login
$verification_status = getVerificationStatus($user_id);

if ($verification_status === 'unverified') {
    echo '<script>
            alert("Your account is not verified. Please verify your account to be able to report an item.");
            window.location="update_profile.php";
          </script>';
    exit();
}

$sql = "SELECT category_id, category_name FROM item_categories";
$result = $conn->query($sql);

$categories = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[$row['category_id']] = $row['category_name'];
    }
}

$conn->close();
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8 report-item-form">
            <h1 class="text-center">Report An Item</h1>
            <form id="reportItemForm" action="includes/report_item.inc.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="item_name">Item Name:</label>
                    <input type="text" class="form-control" id="item_name" name="item_name" required>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" class="form-control" id="location" name="location" required>
                </div>

                <div class="form-group">
                    <label for="lost_or_found">Lost or Found:</label>
                    <select class="form-control" id="lost_or_found" name="lost_or_found" required>
                        <option value="lost">Lost</option>
                        <option value="found">Found</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="category">Select the category of the Item:</label>
                    <select class="form-control" id="category" name="category" required>
                        <?php
                        foreach ($categories as $category_id => $category_name) {
                            echo "<option value='$category_id'>$category_name</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="item_image">Item Image:</label>
                    <input type="file" class="form-control-file" id="item_image" name="item_image" accept="image/*" onchange="previewImage(this);">
                    <img id="item-image-preview" src="#" alt="Item Image Preview" class="img-fluid mt-2" style="display: none;">
                </div>

                <button class="btn btn-dark" type="submit" onclick="return confirmReport();">Submit</button>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    var file = input.files[0];
    var reader = new FileReader();
    reader.onload = function(e) {
        var imgPreview = document.getElementById('item-image-preview');
        imgPreview.src = e.target.result;
        imgPreview.style.display = 'block';
    };
    if (file) {
        reader.readAsDataURL(file);
    }
}

function confirmReport() {
    return confirm("Are you sure you want to report this item?");
}
</script>

<?php include("footer.php"); ?>
