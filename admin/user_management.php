<!-- user_management.php -->
<?php
// Include necessary files and perform authentication
include("header.php");

// Include database connection
include('../includes/dbh.inc.php');

// Fetch all distinct verification statuses from the database
$verificationStatusQuery = "SELECT DISTINCT verification_status FROM users";
$verificationStatusResult = $conn->query($verificationStatusQuery);
?>

<div class="user-management-container">
    <h2 class="jumbotron text-center">User Management</h2>

    <!-- Filter Form -->
    <form action="" method="get">
        <label for="accountStatus">Filter by Account Status:</label>
        <select name="accountStatus" id="accountStatus" class="form-control">
            <option value="">All</option>
            <option value="enabled"
                <?php echo (isset($_GET['accountStatus']) && $_GET['accountStatus'] == 'enabled') ? 'selected' : ''; ?>>
                Enabled</option>
            <option value="disabled"
                <?php echo (isset($_GET['accountStatus']) && $_GET['accountStatus'] == 'disabled') ? 'selected' : ''; ?>>
                Disabled</option>
        </select>

        <label for="verifiedStatus">Filter by Verification Status:</label>
        <select name="verifiedStatus" id="verifiedStatus" class="form-control">
            <option value="">All</option>
            <option value="verified"
                <?php echo (isset($_GET['verifiedStatus']) && $_GET['verifiedStatus'] == 'verified') ? 'selected' : ''; ?>>
                Verified</option>
            <option value="unverified"
                <?php echo (isset($_GET['verifiedStatus']) && $_GET['verifiedStatus'] == 'unverified') ? 'selected' : ''; ?>>
                Unverified</option>
            <option value="unverified"
                <?php echo (isset($_GET['verifiedStatus']) && $_GET['verifiedStatus'] == 'pending') ? 'selected' : ''; ?>>
                Pending</option>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>

        <?php if (isset($_GET['accountStatus']) || isset($_GET['verifiedStatus'])) : ?>
        <a href="user_management.php" class="btn btn-secondary">Show All Users</a>
        <?php endif; ?>
    </form>

    <!-- Search Form -->
    <form action="" method="get" class="mt-3" onsubmit="return validateSearchForm()">
        <label for="matriculationNumber">Search by Matriculation Number:</label>
        <input type="text" name="matriculationNumber" id="matriculationNumber" class="form-control"
            placeholder="Enter Matriculation Number" required>
        <button type="submit" class="btn btn-primary mt-2">Search</button>
    </form>

    <script>
    // JavaScript function to validate the search form
    function validateSearchForm() {
        var matriculationNumber = document.getElementById('matriculationNumber').value.trim();

        if (matriculationNumber === '') {
            alert('Please enter Matriculation Number for search.');
            return false;
        }

        return true;
    }
    </script>

    <?php
    // Fetch users based on the selected status, matriculation number, and verification status, if any
    $filterCondition = '';
    $searchCondition = '';
    $verificationCondition = '';

    if (isset($_GET['accountStatus']) && !empty($_GET['accountStatus'])) {
        $selectedStatus = $conn->real_escape_string($_GET['accountStatus']);
        $filterCondition = " account_status = '$selectedStatus'";
    }

    if (isset($_GET['matriculationNumber']) && !empty($_GET['matriculationNumber'])) {
        $searchedMatriculationNumber = $conn->real_escape_string($_GET['matriculationNumber']);
        $searchCondition = "matriculation_number LIKE '%$searchedMatriculationNumber%'";
    }

    if (isset($_GET['verifiedStatus']) && ($_GET['verifiedStatus'] === 'verified' || $_GET['verifiedStatus'] === 'unverified')) {
        $selectedVerificationStatus = $conn->real_escape_string($_GET['verifiedStatus']);
        $verificationCondition = "verification_status = '$selectedVerificationStatus'";
    }

    $sql = "SELECT * FROM users";

    // Add conditions to the SQL query
    $conditions = array_filter([$filterCondition, $searchCondition, $verificationCondition]);
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    
    // Print the SQL query for debugging
    echo "SQL Query: " . $sql;
    
    $result = $conn->query($sql);
            
    // Check for errors in the query execution
    if (!$result) {
        echo "Error: " . $conn->error;
    }
        ?>

    <?php if ($result->num_rows > 0) : ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <!-- Table headers remain unchanged -->
                    <th>User ID</th>
                    <th>Profile Picture</th>
                    <th>Matriculation Number</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Gender</th>
                    <th>Account Status</th>
                    <th>Verification Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Table body remains unchanged -->
                <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['user_id']; ?></td>
                    <td>
                        <?php
            if (!empty($row['profile_picture'])) {
                $dir = "../assets/images/profile_pictures/";
                echo '<img src="' . $dir . $row['profile_picture'] . '" alt="Profile Picture" width="50" height="50">';
            } else {
                echo 'No Image';
            }
            ?>
                    </td>
                    <td><?php echo $row['matriculation_number']; ?></td>
                    <td><?php echo $row['first_name']; ?></td>
                    <td><?php echo $row['middle_name']; ?></td>
                    <td><?php echo $row['last_name']; ?></td>
                    <td><a href="mailto:<?php echo $row['email']; ?>"><?php echo $row['email']; ?></a></td>
                    <td><a href="tel:<?php echo $row['phone_number']; ?>"><?php echo $row['phone_number']; ?></a></td>
                    <td><?php echo $row['gender']; ?></td>
                    <td><?php echo $row['account_status']; ?></td>
                    <td><?php echo $row['verification_status']; ?></td>
                    <td>
                        <button type="button" class="btn btn-primary edit-btn" data-toggle="modal"
                            data-target="#editModal" data-user-id="<?php echo $row['user_id']; ?>"
                            data-first-name="<?php echo $row['first_name']; ?>"
                            data-middle-name="<?php echo $row['middle_name']; ?>"
                            data-last-name="<?php echo $row['last_name']; ?>"
                            data-gender="<?php echo $row['gender']; ?>" data-email="<?php echo $row['email']; ?>"
                            data-matriculation-number="<?php echo $row['matriculation_number']; ?>"
                            data-phone-number="<?php echo $row['phone_number']; ?>" onclick="openEditModal(this)">
                            Edit
                        </button>

                        <script>
                        // JavaScript function to handle the edit button click event
                        function openEditModal(button) {
                            var userId = button.getAttribute('data-user-id');
                            var firstName = button.getAttribute('data-first-name');
                            var middleName = button.getAttribute('data-middle-name');
                            var lastName = button.getAttribute('data-last-name');
                            var gender = button.getAttribute('data-gender');
                            var email = button.getAttribute('data-email');
                            var matriculationNumber = button.getAttribute('data-matriculation-number');
                            var phoneNumber = button.getAttribute('data-phone-number');

                            // Set the input values when the modal closeis shown
                            document.getElementById('editUserId').value = userId;
                            document.getElementById('editFirstName').value = firstName;
                            document.getElementById('editMiddleName').value = middleName;
                            document.getElementById('editLastName').value = lastName;
                            document.getElementById('editGender').value = gender;
                            document.getElementById('editEmail').value = email;
                            document.getElementById('editMatriculationNumber').value = matriculationNumber;
                            document.getElementById('editPhoneNumber').value = phoneNumber;

                            // Open the modal
                            $('#editModal').modal('show');
                        }
                        </script>
                        <?php if ($row['account_status'] == 'disabled') : ?>
                        <a class="btn btn-success" href="?action=enable&user_id=<?php echo $row['user_id']; ?>"
                            onclick="return confirm('Are you sure you want to enable this user?')">Enable</a>
                        <?php else : ?>
                        <a class="btn btn-warning" href="?action=disable&user_id=<?php echo $row['user_id']; ?>"
                            onclick="return confirm('Are you sure you want to disable this user?')">Disable</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else : ?>
    <p>No users found.</p>
    <?php endif; ?>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                <div class="alert alert-warning" role="alert">Click outside the popup modal to Close</div>
            </div>
            <div class="modal-body">
                <!--form fields for editing user data here -->
                <form action="edit_user.php" method="post">
                    <input type="hidden" name="user_id" id="editUserId" value="">

                    <div class="form-group">
                        <label for="editFirstName">First Name:</label>
                        <input type="text" class="form-control" id="editFirstName" name="editFirstName" value="">
                    </div>

                    <div class="form-group">
                        <label for="editMiddleName">Middle Name:</label>
                        <input type="text" class="form-control" id="editMiddleName" name="editMiddleName" value="">
                    </div>

                    <div class="form-group">
                        <label for="editLastName">Last Name:</label>
                        <input type="text" class="form-control" id="editLastName" name="editLastName" value="">
                    </div>

                    <div class="form-group">
                        <label for="editGender">Gender:</label>
                        <input type="text" class="form-control" id="editGender" name="editGender" value="">
                    </div>

                    <div class="form-group">
                        <label for="editEmail">Email:</label>
                        <input type="email" class="form-control" id="editEmail" name="editEmail" value="">
                    </div>

                    <div class="form-group">
                        <label for="editMatriculationNumber">Matriculation Number:</label>
                        <input type="text" class="form-control" id="editMatriculationNumber"
                            name="editMatriculationNumber" value="">
                    </div>

                    <div class="form-group">
                        <label for="editPhoneNumber">Phone Number:</label>
                        <input type="tel" class="form-control" id="editPhoneNumber" name="editPhoneNumber" value="">
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>

                <script>
                // Set the input values when the modal is shown
                var editModal = new bootstrap.Modal(document.getElementById('editModal'));

                document.querySelectorAll('.edit-btn').forEach(function(button) {
                    button.addEventListener('click', function() {
                        var button = this;
                        document.getElementById('editUserId').value = button.getAttribute(
                            'data-user-id');
                        document.getElementById('editFirstName').value = button.getAttribute(
                            'data-first-name');
                        document.getElementById('editMiddleName').value = button.getAttribute(
                            'data-middle-name');
                        document.getElementById('editLastName').value = button.getAttribute(
                            'data-last-name');
                        document.getElementById('editGender').value = button.getAttribute(
                            'data-gender');
                        document.getElementById('editEmail').value = button.getAttribute('data-email');
                        document.getElementById('editMatriculationNumber').value = button.getAttribute(
                            'data-matriculation-number');
                        document.getElementById('editPhoneNumber').value = button.getAttribute(
                            'data-phone-number');

                        // Show the modal
                        editModal.show();
                    });
                });
                </script>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
// Handle enable/disable action
if (isset($_GET['action']) && isset($_GET['user_id'])) {
    $user_id = $conn->real_escape_string($_GET['user_id']);

    // Update the account_status based on the action
    if ($_GET['action'] == 'disable') {
        $newStatus = 'disabled';
    } elseif ($_GET['action'] == 'enable') {
        $newStatus = 'enabled';
    }

    $updateSql = "UPDATE users SET account_status = '$newStatus' WHERE user_id = $user_id";
    $updateResult = $conn->query($updateSql);

    if ($updateResult) {
        $actionMessage = ($_GET['action'] == 'disable') ? 'disabled' : 'enabled';
        echo '<script>alert("User ' . $actionMessage . ' successfully.");</script>';
    } else {
        echo '<script>alert("Error updating user status: ' . $conn->error . '");</script>';
    }

    // Redirect back to the user_management.php page
    echo '<script>window.location.href = "user_management.php";</script>';
    exit();
}

include("../footer.php");
?>