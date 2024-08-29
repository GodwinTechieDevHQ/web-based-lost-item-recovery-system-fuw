<?php
// item_management.php
include("header.php"); // Include admin header

// Include database connection
include('../includes/dbh.inc.php');

// Fetch all item categories from the database
$categoryQuery = "SELECT * FROM item_categories";
$categoryResult = $conn->query($categoryQuery);

?>

<div class="admin-content">
    <h2 class="jumbotron text-center">Item Management</h2>

    <!-- Warning/Notice Text -->
    <div class="alert alert-warning text-center fw-bold" role="alert">
        Please make sure to reload the page if changes do not reflect immediately.
    </div>
    <!-- Display Categories -->
    <?php if ($categoryResult->num_rows > 0) : ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Category ID</th>
                    <th>Category Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $categoryResult->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['category_id']; ?></td>
                    <td><?php echo $row['category_name']; ?></td>
                    <td>
                        <button type="button" class="btn btn-primary"
                            onclick="openEditModal(<?php echo $row['category_id']; ?>, '<?php echo $row['category_name']; ?>')">
                            Edit
                        </button>
                        <a class="btn btn-danger" href="?action=delete&category_id=<?php echo $row['category_id']; ?>"
                            onclick="return confirmDelete()">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else : ?>
    <p>No categories found.</p>
    <?php endif; ?>

    <!-- Add Category Form -->
    <form action="item_management.php" method="post" class="mt-3">
        <h3>Add Category</h3>
        <div class="form-group">
            <label for="newCategoryName">Category Name:</label>
            <input type="text" class="form-control" id="newCategoryName" name="newCategoryName" required>
        </div>
        <button type="submit" class="btn btn-success" onclick="return showSuccess()">Add Category</button>
    </form>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Edit Category Form -->
                    <form action="item_management.php" method="post">
                        <input type="hidden" name="editCategoryID" id="editCategoryID">
                        <div class="form-group">
                            <label for="editCategoryName">Category Name:</label>
                            <input type="text" class="form-control" id="editCategoryName" name="editCategoryName"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary" onclick="return showSuccess()">Save Changes
                        </button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    return confirm('Are you sure you want to delete this category?');
}

function showSuccess() {
    alert('Category added successfully.');
    return true;
}
</script>

<?php
// Handle add, edit, and delete actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['newCategoryName'])) {
        // Add new category
        $newCategoryName = $conn->real_escape_string($_POST['newCategoryName']);
        $insertQuery = "INSERT INTO item_categories (category_name) VALUES ('$newCategoryName')";
        $insertResult = $conn->query($insertQuery);
    } elseif (isset($_POST['editCategoryID']) && isset($_POST['editCategoryName'])) {
        // Edit category
        $editCategoryID = $conn->real_escape_string($_POST['editCategoryID']);
        $editCategoryName = $conn->real_escape_string($_POST['editCategoryName']);
        $updateQuery = "UPDATE item_categories SET category_name = '$editCategoryName' WHERE category_id = $editCategoryID";
        $updateResult = $conn->query($updateQuery);
    }
}

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['category_id'])) {
    $deleteCategoryID = $conn->real_escape_string($_GET['category_id']);
    $deleteQuery = "DELETE FROM item_categories WHERE category_id = $deleteCategoryID";
    $deleteResult = $conn->query($deleteQuery);
}

include("footer.php"); // Include admin footer
?>