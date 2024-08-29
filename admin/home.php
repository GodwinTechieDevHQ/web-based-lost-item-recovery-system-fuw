<!-- home.php -->
<?php
include("header.php");

// Check if the user is logged in
include("../includes/login_check.inc.php");

// Connect to your database (you should replace these with your actual database credentials)
include('../includes/dbh.inc.php');

// Function to fetch 10 latest lost items
$dir = "../assets/images/";

function fetchLatestLostItems($conn)
{
    $sql = "SELECT item_id, item_name, item_description, location, item_image, status, date_lost 
            FROM lost_items 
            ORDER BY date_lost DESC 
            LIMIT 10";
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

?>

<div class="in-body">
    <p>Hello <?php echo $_SESSION['first_name']; ?></p>
    <div class="items-lost-today">
        <h2>Latest Lost Items</h2>
        <div class="container-fluid" style="overflow-x: auto; white-space: nowrap;">
            <?php
            $items = fetchLatestLostItems($conn);
            foreach ($items as $item) {
                echo '<div class="item" style="display: inline-block; white-space: normal; max-width: 200px;">';
                echo '<img src="'. $dir . $item["item_image"] . '" alt="' . $item["item_name"] . '">';
                echo '<div class="item-details">';
                echo '<p>Status: ' . $item["status"] . '</p>';
                echo '<p>Name: ' . $item["item_name"] . '</p>';
                echo '<p>Description: ' . $item["item_description"] . '</p>';
                echo '<p>Location: ' . $item["location"] . '</p>';
                echo '<p>Date: ' . $item["date_lost"] . '</p>';
                echo '</div>';
                echo '</div>';
            }
            ?>
            <!-- See More Link -->
            <a href="lost_and_found.php" class="see-more-link">See More</a>
        </div>
    </div>

    <div class="recent-feedback">
        <h2>Recent Feedback</h2>
        <ul>
            <?php
            $feedback = fetchRecentFeedback($conn);
            foreach ($feedback as $entry) {
                echo '<li>';
                echo '<p><strong>' . $entry["first_name"] . ':</strong> ' . $entry["feedback_text"] . '</p>';
                echo '<p>Posted on: ' . $entry["feedback_date"] . '</p>';
                echo '</li>';
            }
            ?>
        </ul>
    </div>
</div>

<?php include("footer.php"); ?>