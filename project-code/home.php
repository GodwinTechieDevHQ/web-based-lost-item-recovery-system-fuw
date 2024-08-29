<!-- home.php -->
<?php
include("header.php");

// Check if the user is logged in
include("includes/login_check.inc.php");

// Connect to your database (you should replace these with your actual database credentials)
include('includes/dbh.inc.php');

// Function to fetch 10 latest lost items
$dir = "assets/images/items/";

function fetchLatestLostItems($conn)
{
    $sql = "SELECT item_id, item_name, item_description, location, item_image, status, date_lost 
            FROM lost_items 
            ORDER BY date_lost DESC 
            LIMIT 4";
    $result = $conn->query($sql);

    $items = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }

    return $items;
}

// Function to fetch recent feedback
function fetchRecentFeedback($conn)
{
    $sql = "SELECT users.first_name, feedback.feedback_text, feedback.feedback_date 
            FROM feedback
            INNER JOIN users ON feedback.user_id = users.user_id
            ORDER BY feedback.feedback_date DESC
            LIMIT 5"; // You can change the LIMIT to control the number of feedback entries displayed

    $result = $conn->query($sql);

    $feedback = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $feedback[] = $row;
        }
    }

    return $feedback;
}

// Check if the user has a profile picture
$user_id = $_SESSION['user_id'];
$sqlProfile = "SELECT profile_picture FROM users WHERE user_id = '$user_id'";
$resultProfile = $conn->query($sqlProfile);

$hasProfilePicture = false;

if ($resultProfile->num_rows > 0) {
$rowProfile = $resultProfile->fetch_assoc();
$hasProfilePicture = !empty($rowProfile['profile_picture']);
}

?>

<div class="in-body">
    <?php
    // Display a message if the user doesn't have a profile picture
    if (!$hasProfilePicture) {
        echo '<div class="alert alert-warning" role="alert">';
        echo 'Hello ' . $_SESSION['first_name'] . '! Please upload a picture of your face and a picture of your ID card for verification. ';
        echo '<a href="update_profile.php" class="alert-link">Verify account</a>';
        echo '</div>';
    }
    ?>

    <div class="items-lost-today">
        <h2>Recently Reported Items</h2>
        <div class="container-fluid" style="overflow-x: auto; white-space: nowrap;">
            <?php
            $items = fetchLatestLostItems($conn);
            foreach ($items as $item) {
                echo '<div class="item" style="display: inline-block; white-space: normal; max-width: 200px; margin:4px;">';
                if ($item["item_image"] !== "fileupload: 4") {
                    echo '<img class="item_img" src="' . "assets/images/items/" . $item["item_image"] . '" style="width:100%;">';    
                }
                else
                {
                    echo "<h5>No image uploaded!</h5>";
                }                
                echo '<div class="item-details">';
                echo '<p><b>Status:</b> ' . $item["status"] . '</p>';
                echo '<p><b>Name:</b> ' . $item["item_name"] . '</p>';
                echo '<p><b>Description:</b> ' . $item["item_description"] . '</p>';
                echo '<p><b>Location:</b> ' . $item["location"] . '</p>';
                echo '<p><b>Date:</b> ' . $item["date_lost"] . '</p>';
                echo '</div>';
                echo '</div>';
            }
            ?>
            <!-- See More Link -->
            <a href="lost_and_found.php" class="see-more-link">See More</a>
        </div>
    </div>

</div>

<?php include("footer.php"); ?>