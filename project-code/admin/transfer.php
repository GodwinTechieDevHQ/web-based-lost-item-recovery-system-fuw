<?php
// Include necessary files
include("header.php");

// Check if the user is logged in
include("includes/login_check.inc.php");

// Include database connection
include('includes/dbh.inc.php');

// Retrieve user ID from the session
$user_id = $_SESSION['user_id'];

// Capture other_user_id from the URL
$other_user_id = isset($_GET['other_user_id']) ? $_GET['other_user_id'] : null;

// Set other_user_id in the session
$_SESSION['other_user_id'] = $other_user_id;

// Fetch the list of items in the user's possession
$items = fetchUserItems($conn, $user_id);

function fetchUserItems($conn, $user_id) {
    // Implement your database query to fetch items in the user's possession
    $sql = "SELECT item_id, item_name, item_image FROM lost_items WHERE owner_id = $user_id";
    
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->get_result();

    $items = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();

    return $items;
}
?>

<!-- HTML and Form for Transfer -->
<div class="transfer-container">
    <h2>Select an Item to Transfer</h2>
    <form id="transferForm">
        <label for="itemSelect">Select Item:</label>
        <select id="itemSelect" name="item_id">
            <?php foreach ($items as $item) : ?>
            <option value="<?php echo $item['item_id']; ?>"><?php echo $item['item_name']; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="button" id="showDetailsButton">Show Item Details</button>
        <button type="button" class="initiateTransferButton" style="display: none;">Initiate Transfer</button>
    </form>

    <!-- Display details of the selected item -->
    <div id="itemDetails" style="display: none;">
        <h3>Item Details</h3>
        <p id="itemName"></p>
        <img src="" id="itemImage" class="item_img" alt="Item Image" style="width: 70%;">
    </div>

    <!-- Display success message and redirect link -->
    <div id="transferSuccessMessage" style="display: none;">

        <p id="successMessage">Item transferred successfully!</p>
        <a id="profileLink" href="transactions.php">Go to Transactions</a>
    </div>
</div>

<script src="jquery.js"></script>
<script>
$(document).ready(function() {
    // Function to show item details when the button is clicked
    $("#showDetailsButton").click(function() {
        var selectedItemId = $("#itemSelect").val();
        var selectedItem = findItemById(selectedItemId);

        if (selectedItem) {
            $("#itemName").text("Item Name: " + selectedItem.item_name);

            // Concatenate base URL with the item image filename
            var itemImagePath = "assets/images/items/" + selectedItem.item_image;

            $("#itemImage").attr("src", itemImagePath);
            $("#itemDetails").show();
            $(".initiateTransferButton").show();
        }
    });

    // Function to initiate transfer when the button is clicked
    $(".initiateTransferButton").click(function() {
        var selectedItemId = $("#itemSelect").val();
        var confirmation = confirm(
            "Are you sure you want to transfer this item?");

        if (confirmation) {
            // Make an AJAX request to transfer_confirm.php
            $.post("transfer_confirm.php", {
                user_id: <?php echo $_SESSION['user_id']; ?>,
                other_user_id: <?php echo $other_user_id; ?>,
                item_id: selectedItemId
            }, function(response) {
                console.log(response); // Log the response to the console
                // Handle the response from the server
                if (response.success) {
                    // Show success message and redirect link
                    $("#transferSuccessMessage").show();
                    $("#profileLink").attr("href", "profile.php");
                    // Hide other elements
                    $("#itemDetails, .initiateTransferButton").hide();
                } else {
                    alert(
                        "Transaction already exists! Notify the receiver to check for the transaction"
                    );
                }
            }, "json");
        }
    });

    // Your JavaScript code for handling the transfer process will go here

    // Function to find an item by its ID in the items array
    function findItemById(itemId) {
        return <?php echo json_encode($items); ?>.find(function(item) {
            return item.item_id == itemId;
        });
    }
});
</script>

<?php include("footer.php"); ?>