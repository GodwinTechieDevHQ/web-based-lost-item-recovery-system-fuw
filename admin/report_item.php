<?php
include("header.php");
include("../includes/login_check.inc.php");
include("../includes/dbh.inc.php");

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
            <form action="../includes/report_item.inc.php" method="post" enctype="multipart/form-data">
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

                <button class="btn btn-dark" type="submit">Submit</button>
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
</script>

<?php include("../footer.php"); ?>