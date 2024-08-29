<?php
include("header.php");
include("includes/login_check.inc.php");
include('includes/dbh.inc.php');

// Query the database to retrieve item categories
$sql = "SELECT category_id, category_name FROM item_categories";
$result = $conn->query($sql);

$categories = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[$row['category_id']] = htmlspecialchars($row['category_name']);
    }
}

// Function to fetch lost and found items based on category and date
function fetchItems($conn, $category, $date)
{
    $sql = "SELECT item_id, item_name, item_description, location, item_image, status, date_lost, owner_id FROM lost_items WHERE date_lost >= ?";

    if ($category !== "all") {
        $sql .= " AND category_id = ?";
    }

    $stmt = $conn->prepare($sql);
    
    if ($category === "all") {
        $stmt->bind_param("s", $date);
    } else {
        $stmt->bind_param("ss", $date, $category);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $items = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }

    $stmt->close();

    return $items;
}

$category = "all"; // Default category (all categories)
$date = date("Y-m-d") . " 00:00"; // Default date (12 am today)

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
                    echo "<option value='$category_id'" . ($category_id == $category ? ' selected' : '') . ">$category_name</option>";
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

                $stmt = $conn->prepare("SELECT first_name, last_name, profile_picture FROM users WHERE user_id = ?");
                $stmt->bind_param("i", $other_user_id);
                $stmt->execute();
                $user_result = $stmt->get_result();
                
                if ($user_result->num_rows > 0) {
                    $user_info = $user_result->fetch_assoc();
                    $user_full_name = htmlspecialchars($user_info["first_name"] . " " . $user_info["last_name"]);
                    $profile_picture = "assets/images/profile_pictures/" . htmlspecialchars($user_info["profile_picture"]);
                } else {
                    $user_full_name = "Unknown User";
                    $profile_picture = "assets/images/default_profile.png";
                }

                $item_image = "assets/images/items/" . htmlspecialchars($item["item_image"]);
                $status = htmlspecialchars($item["status"]);
                $item_name = htmlspecialchars($item["item_name"]);
                $item_description = htmlspecialchars($item["item_description"]);
                $location = htmlspecialchars($item["location"]);
                $date_lost = date("d-m-Y H:i", strtotime($item["date_lost"]));

                // Check if the current user is the owner of the item
                $isCurrentUserOwner = $_SESSION['user_id'] == $other_user_id;

                echo '<div class="item">';
                echo '<a href="person.php?other_user_id=' . urlencode($other_user_id) . '">';
                echo '<img id="prof" src="' . htmlspecialchars($profile_picture) . '" alt="' . htmlspecialchars($user_full_name) . '">';
                echo '<p class="fullname">' . htmlspecialchars($user_full_name) . '</p>';
                echo '</a>';
                echo '<img class="item_img" src="' . htmlspecialchars($item_image) . '" alt="' . htmlspecialchars($item_name) . '" style="width:100%;">';    
                echo '<div class="item-details">';
                echo '<h6 class="fw-bold">Status: </h6>';
                echo '<p>' . $status . '</p>';
                echo '<h6 class="fw-bold">Item Name: </h6>';
                echo '<p>' . $item_name . '</p>';
                echo '<h6 class="fw-bold">Description: </h6>';
                echo '<p>' . $item_description . '</p>';
                echo '<h6 class="fw-bold">Location: </h6>';
                echo '<p>' . $location . '</p>';
                echo '<p><b>Date: </b>' . $date_lost . '</p>';

                if (!$isCurrentUserOwner) {
                    echo '<a href="private_chat.php?other_user_id=' . urlencode($other_user_id) . '">Chat-up</a>';
                }

                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>

<?php
include("footer.php");
?>
