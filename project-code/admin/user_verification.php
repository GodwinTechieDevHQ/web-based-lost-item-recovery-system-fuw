<!-- user_verification.php -->
<?php
include("header.php"); // Include admin header
include('../includes/dbh.inc.php');
?>

<div class="admin-content">
    <h2 class="jumbotron text-center">User Verification</h2>

    <!-- Display Unverified Users -->
    <?php
    $unverifiedUsersQuery = "SELECT * FROM users WHERE verification_status = 'pending'";
    $unverifiedUsersResult = $conn->query($unverifiedUsersQuery);
    ?>

    <?php if ($unverifiedUsersResult->num_rows > 0) : ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-sm">
            <thead class="thead-dark">
                <tr>
                    <th>User ID</th>
                    <th>Profile Picture</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Matriculation Number</th>
                    <th>Verification Document</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $unverifiedUsersResult->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $user['user_id']; ?></td>
                    <td>
                        <?php
                                if (!empty($user['profile_picture'])) {
                                    echo '<img src="../assets/images/profile_pictures/' . $user['profile_picture'] . '" alt="Profile Picture" width="50" height="50">';
                                } else {
                                    echo 'No Image';
                                }
                                ?>
                    </td>
                    <td><?php echo $user['first_name']; ?></td>
                    <td><?php echo $user['middle_name']; ?></td>
                    <td><?php echo $user['last_name']; ?></td>
                    <td><?php echo $user['gender']; ?></td>
                    <td><?php echo $user['matriculation_number']; ?></td>
                    <td><?php echo $user['verification_document']; ?></td>
                    <td>
                        <!-- <button type="button" class="btn btn-primary btn-sm"
                            onclick="verifyUser(<php echo $user['user_id']; ?>)">Verify</button> -->
                        <button type="button" class="btn btn-success btn-sm"
                            onclick="compareImages('<?php echo $user['profile_picture']; ?>', '<?php echo $user['verification_document']; ?>', '<?php echo $user['user_id']; ?>')">Verify</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else : ?>
    <p>No unverified users found.</p>
    <?php endif; ?>
</div>

<!-- Include Bootstrap CSS and JS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<!-- Image Compare Modal -->
<div class="modal fade" id="imageCompareModal" tabindex="-1" role="dialog" aria-labelledby="imageCompareModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageCompareModalLabel">Image Comparison</h5>
                <button type="button" class="close" aria-label="Close" onclick="closeImageCompareModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Profile Picture</h6>
                        <img src="../assets/images/profile_pictures/<?php echo $user['profile_picture']; ?>"
                            alt="Profile Picture" id="profilePicture" class="img-fluid">
                    </div>
                    <div class="col-md-6">
                        <h6>Verification Document</h6>
                        <img src="../assets/images/id_cards/<?php echo $user['verification_document']; ?>"
                            alt="Verification Document" id="verificationDocument" class="img-fluid">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="verifyButton"
                    onclick="verifyUserAction()">Verify</button>
                <button type="button" class="btn btn-danger" id="rejectButton"
                    onclick="rejectUserAction()">Reject</button>
                <button type="button" class="btn btn-secondary" onclick="closeImageCompareModal()">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function verifyUser(userId) {
    if (confirm('Are you sure you want to verify this user?')) {
        // Set the user ID as a data attribute in the modal
        $('#imageCompareModal').data('user-id', userId);

        // Open the modal
        $('#imageCompareModal').modal('show');
    }

    var profilePictureSrc = "../assets/images/profile_pictures/" + profilePicture;
    var verificationDocumentSrc = "../assets/images/id_cards/" + verificationDocument;

    // Update the image sources in the modal
    document.getElementById("profilePicture").src = profilePictureSrc;
    document.getElementById("verificationDocument").src = verificationDocumentSrc;

    // Open the modal
    $('#imageCompareModal').modal('show');

}

function compareImages(profilePicture, verificationDocument, userId) {

    // Set the user ID as a data attribute in the modal
    $('#imageCompareModal').data('user-id', userId);

    var profilePictureSrc = "../assets/images/profile_pictures/" + profilePicture;
    var verificationDocumentSrc = "../assets/images/id_cards/" + verificationDocument;

    // Update the image sources in the modal
    document.getElementById("profilePicture").src = profilePictureSrc;
    document.getElementById("verificationDocument").src = verificationDocumentSrc;

    // Open the modal
    $('#imageCompareModal').modal('show');

}

function rejectUserAction() {
    if (confirm('Are you sure you want to reject this user?')) {
        // Retrieve the user ID from the data attribute
        var userId = $('#imageCompareModal').data('user-id');

        // Perform reject action, delete profile picture and ID card
        // For simplicity, let's redirect to refresh the page after the confirmation
        window.location.href = "user_verification.php?action=reject&user_id=" + userId;
    }
}

function closeImageCompareModal() {
    // Close the modal
    $('#imageCompareModal').modal('hide');
}

function verifyUserAction() {
    if (confirm('Are you sure you want to verify this user?')) {

        // Retrieve the user ID from the data attribute
        var userId = $('#imageCompareModal').data('user-id');

        // Perform verification action, you can use AJAX to update the database without refreshing the page
        // For simplicity, let's redirect to refresh the page after the confirmation
        window.location.href = "user_verification.php?action=verify&user_id=" + userId;
    }
}
</script>

<?php
// Handle verification action
if (isset($_GET['action']) && $_GET['action'] == 'verify' && isset($_GET['user_id'])) {
    $verifiedUserId = $conn->real_escape_string($_GET['user_id']);
    $updateVerificationQuery = "UPDATE users SET verification_status = 'verified' WHERE user_id = $verifiedUserId";
    $updateVerificationResult = $conn->query($updateVerificationQuery);

    // You can add further error handling and success messages here if needed

    // Redirect to refresh the page
    echo '<script>window.location.href = "user_verification.php";</script>';
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'reject' && isset($_GET['user_id'])) {
    $rejectedUserId = $conn->real_escape_string($_GET['user_id']);

    // Delete profile picture and ID card
    $getUserInfoQuery = "SELECT profile_picture, verification_document FROM users WHERE user_id = $rejectedUserId";
    $userInfoResult = $conn->query($getUserInfoQuery);

    if ($userInfoResult->num_rows > 0) {
        $userInfo = $userInfoResult->fetch_assoc();

        // Delete profile picture
        $profilePicturePath = "../assets/images/profile_pictures/" . $userInfo['profile_picture'];
        if (file_exists($profilePicturePath)) {
            unlink($profilePicturePath);
        }

        // Delete ID card
        $idCardPath = "../assets/images/id_cards/" . $userInfo['verification_document'];
        if (file_exists($idCardPath)) {
            unlink($idCardPath);
        }
    }

    // Update verification status to "rejected" and set profile_picture and verification_document to null
    $updateVerificationQuery = "UPDATE users SET verification_status = 'rejected', profile_picture = NULL, verification_document = NULL WHERE user_id = $rejectedUserId";
    $updateVerificationResult = $conn->query($updateVerificationQuery);

    // You can add further error handling and success messages here if needed

    // Redirect to refresh the page
    echo '<script>window.location.href = "user_verification.php";</script>';
    exit();
}


include("footer.php"); // Include admin footer
?>