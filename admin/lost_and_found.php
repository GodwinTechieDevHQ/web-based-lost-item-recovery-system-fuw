<?php
include("header.php");
include("../includes/login_check.inc.php");

// Connect to your database (you should replace these with your actual database credentials)
include('../includes/dbh.inc.php');

// Query the database to retrieve item categories
$sql = "SELECT category_id, category_name FROM item_categories";
$result = $conn->query($sql);
$dir = "../assets/images/";

$categories = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[$row['category_id']] = $row['category_name'];
    }
}

// Function to fetch lost and found items based on category and date
function fetchItems($conn, $category, $date)
{
    $sql = "SELECT item_id, item_name, item_description, location, item_image, status, date_lost, owner_id FROM lost_items WHERE date_lost >= '$date' ORDER BY date_lost DESC";
    if ($category !== "all") {
        $sql = "SELECT item_id, item_name, item_description, location, item_image, status, date_lost, owner_id FROM lost_items WHERE category_id = '$category' AND date_lost >= '$date' ORDER BY date_lost DESC";
    }

    $result = $conn->query($sql);

    $items = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }

    return $items;
}

$category = "all"; // Default category (all categories)
$date = date("d-m-Y") . " 00:00"; // Default date (12 am today)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST["category"];
    $date = $_POST["date"];
}
?>

<div class="background-image">
    <div class="lost-and-found-container">
        <h1>Lost and Found Items</h1>
        <form action="lost_and_found.php" method="post">
            <label for="category">Select Category:</label>
            <select name="category" required>
                <option value="all">All Categories</option>
                <?php
                foreach ($categories as $category_id => $category_name) {
                    echo "<option value='$category_id'>$category_name</option>";
                }
                ?>
            </select>
            <label for="date">Select Date and Time:</label>
            <input type="datetime-local" name="date" id="date" value="<?php echo date("Y-m-d") . "T00:00"; ?>">
            <button type="submit">Filter</button>
        </form>
        <div class="item-list">
            <?php
            $items = fetchItems($conn, $category, $date);
            foreach ($items as $item) {
                // Fetch user information based on the item's user_id
                $other_user_id = $item["owner_id"];

                $user_query = "SELECT first_name, last_name, profile_picture FROM users WHERE user_id = '$other_user_id'";
                $user_result = $conn->query($user_query);
                if ($user_result->num_rows > 0) {
                    $user_info = $user_result->fetch_assoc();
                    $user_full_name = $user_info["first_name"] . " " . $user_info["last_name"];
                    $dir = "../assets/images/profile_pictures/";
                    $profile_picture = $dir . $user_info["profile_picture"];
                } else {
                    $user_full_name = "Unknown User";
                    $profile_picture = "../assets/images/default_profile.png";
                }

                echo '<div class="item">';
                echo '<a href="person.php?other_user_id=' . $other_user_id . '">';
                echo '<img id="prof" src="' . $profile_picture . '" alt="' . $user_full_name . '">';
                echo '<p class="fullname">' . $user_full_name . '</p>';
                echo '</a>';
                echo '<img class="item_img" src="' . "../assets/images/items/" . $item["item_image"] . '" alt="' . $item["item_name"] . '">';    
                echo '<div class="item-details">';
                echo '<h6 class="fw-bold ">Status: </h6>';
                echo '<p>' . $item["status"] . '</p>';
                echo '<h6 class="fw-bold ">Item Name:  </h6>';
                echo '<p>' . $item["item_name"] . '</p>';
                echo '<h6 class="fw-bold ">Description:  </h6>';
                echo '<p>' . $item["item_description"] . '</p>';

                echo '<h6 class="fw-bold ">Location:  </h6>';

                echo '<p> ' . $item["location"] . '</p>';
                echo '<p><b>Date: </b>' . date("d-m-Y H:i", strtotime($item["date_lost"])) . '</p> ';

                // Check if the current user is the owner of the item
                $isCurrentUserOwner = $_SESSION['user_id'] == $other_user_id;

                // Add a "Chat" link if the current user is not the owner
                if (!$isCurrentUserOwner) {
                    echo '<a href="private_chat.php?other_user_id=' . $other_user_id . '">Chat-up</a>';
                }

                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>

<?php
include("../footer.php");
?>