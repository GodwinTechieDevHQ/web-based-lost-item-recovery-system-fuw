<?php
include("header.php");

// Check if the user is logged in
include("../includes/login_check.inc.php");

// Connect to your database (replace with your actual credentials)
include('../includes/dbh.inc.php');

// Retrieve user IDs from the session
$user_id = $_SESSION['user_id'];

// Fetch the other user ID from the URL
$other_user_id = isset($_GET['other_user_id']) ? $_GET['other_user_id'] : null;

// Validate that other_user_id is set and is a valid user
if (!$other_user_id) {
    // Redirect to a proper error page or handle the error accordingly
    header("Location: error.php");
    exit();
}

echo '<a class="btn btn-dark" href="javascript:history.back()" style="float:left">Back</a> <br>';

// Fetch other user's name and profile picture from the database
$other_user_info = fetchOtherUserInfo($conn, $other_user_id);

// Function to fetch other user's information
function fetchOtherUserInfo($conn, $other_user_id) {
    // Implement your database query to fetch other user's name and profile picture
    $sql = "SELECT first_name, last_name, profile_picture FROM users WHERE user_id = ?";
    
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $other_user_id);
    $stmt->execute();

    $result = $stmt->get_result();

    $other_user_info = $result->fetch_assoc();

    $stmt->close();

    return $other_user_info;
}
?>

<div class="container mt-4">
    <div class="chat-container rounded">
        <div class="chat-header bg-dark text-white p-3">
            <a href="person.php?other_user_id=<?php echo $other_user_id; ?>">
                <img src="<?php echo "../assets/images/profile_pictures/" . $other_user_info['profile_picture']; ?>"
                    alt="Other User" class="rounded-circle" style="width: 50px; height: 50px;">
            </a>
            <h2 class="ml-3 mb-0"><?php echo $other_user_info['first_name'] . ' ' . $other_user_info['last_name']; ?>
            </h2>
            <a href="transfer.php?other_user_id=<?php echo $other_user_id; ?>" class="btn btn-light">Transfer Item</a>
        </div>

        <button class="latest-button btn btn-primary mb-2" id="latest-button">Latest</button>
        <div id="chat-messages" class="overflow-auto" style="max-height: 400px;">
            <!-- Chat messages will be displayed here -->
        </div>
        <div class="message-input p-3">
            <textarea id="message-textarea" name="message_text" class="form-control"
                placeholder="Type your message"></textarea>
            <input type="hidden" name="other_user_id" value="<?php echo $other_user_id; ?>">
            <button id="send-button" class="btn btn-primary mt-2">Send</button>
        </div>
    </div>
</div>

<!-- JavaScript for auto-reloading messages using AJAX -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- Add Bootstrap JS if not included in your project -->
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script> -->
<script>
// main.js
$(document).ready(function() {
    var chatBox = $("#chat-messages");
    var latestButton = $("#latest-button");
    var isAtBottom = false;

    function formatMessageText(messageText) {
        // Replace newline characters with <br> tags
        return messageText.replace(/\n/g, '<br>');
    }

    function updateChat() {
        $.get("chat.php", {
            user_id: <?php echo $user_id; ?>,
            other_user_id: <?php echo $other_user_id; ?>
        }, function(data) {
            data = formatMessageText(data);
            chatBox.html(data);
            if (isAtBottom) {
                scrollToNewestMessage();
            }
        });
    }

    function scrollToNewestMessage() {
        var scrollHeight = chatBox[0].scrollHeight;
        var scrollSpeed = 2000; // Adjust the speed (in milliseconds) as needed

        chatBox.animate({
            scrollTop: scrollHeight
        }, scrollSpeed, function() {
            // Enable the button after scrolling is complete
            latestButton.prop("disabled", true);
        });
    }

    // Function to scroll to the newest message when the "Latest" button is clicked
    latestButton.click(function() {
        latestButton.prop("disabled", true); // Disable the button during scrolling
        scrollToNewestMessage();
    });

    // Check the scroll position and update the button status in real-time
    chatBox.scroll(function() {
        isAtBottom = chatBox[0].scrollHeight - chatBox.scrollTop() === chatBox.outerHeight();
        latestButton.prop("disabled", isAtBottom);
    });

    // Load initial chat messages and set button status
    updateChat();

    // Function to auto-reload chat messages at a specific interval (e.g., every 5 seconds)
    function autoReloadChat() {
        setInterval(function() {
            updateChat();
        }, 3000); // Adjust the interval (in milliseconds) as needed
    }

    // Start auto-reloading
    autoReloadChat();

    // Send a message
    $("#send-button").click(function() {
        var message = $("#message-textarea").val();
        if (message !== "") {
            $.post("chat.php", {
                message_text: message,
                user_id: <?php echo $user_id; ?>,
                other_user_id: <?php echo $other_user_id; ?>
            }, function() {
                $("#message-textarea").val("");
                updateChat();
            });
        }
    });
});
</script>

<?php include("../footer.php"); ?>