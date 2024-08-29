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
        <div class="col-md-6 report-item-form">
            <h1 class="text-center">Report An Item</h1>
            <form id="reportItemForm" action="includes/report_item.inc.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="item_name">Item Name:</label>
                    <input type="text" class="form-control" name="item_name" required>
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea class="form-control" name="description" rows="4" required></textarea>
                </div>

                <div class="form-group">
                    <label for="location">Location:</label>
                    <input type="text" class="form-control" name="location" required>
                </div>

                <div class="form-group">
                    <label for="report_type">Report To:</label><br>
                    <input type="radio" name="report_type" value="public" checked> Public
                    <input type="radio" name="report_type" value="security"> Security
                </div>

                <div class="form-group">
                    <label for="lost_or_found">Lost or Found:</label>
                    <select class="form-control" name="lost_or_found" required>
                        <option value="lost">Lost</option>
                        <option value="found">Found</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="category">Select the category of the Item:</label>
                    <select class="form-control" name="category" required>
                        <?php
                        foreach ($categories as $category_id => $category_name) {
                            echo "<option value='$category_id'>$category_name</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="item_image">Item Image:</label>
                    <input type="file" class="form-control-file" name="item_image" accept="image/*"
                        onchange="previewImage(this);">
                    <img id="item-image-preview" src="#" alt="Item Image Preview" class="img-fluid mt-2"
                        style="display: none;">
                </div>

                <button class="btn btn-dark" type="button" onclick="confirmReport()">Submit</button>
                <div class="alert-link">
                    Note: You can only report found items to the security department</div>
            </form>

        </div>
    </div>
</div>

<script>
function previewImage(input) {
    var reader = new FileReader();
    reader.onload = function(e) {
        $('#item-image-preview').attr('src', e.target.result).show();
    };
    reader.readAsDataURL(input.files[0]);
}

function confirmReport() {
    if (confirm("Are you sure you want to report this item?")) {
        // If user confirms, submit the form
        document.getElementById("reportItemForm").submit();
    } else {
        // If user cancels, do nothing
    }

    // Show or hide security note based on selected report type
    var reportType = $('input[name="report_type"]:checked').val();
    if (reportType === 'security') {
        $('#securityNote').show();
    } else {
        $('#securityNote').hide();
    }
}
</script>

<?php
include("footer.php");
?>