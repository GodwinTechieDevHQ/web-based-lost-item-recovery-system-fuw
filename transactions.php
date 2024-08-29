<!-- transactions.php -->
<?php
include("header.php");

if (isset($_SESSION['user_id'])) {
    // Connect to your database (replace these with your actual database credentials)
    include("includes/dbh.inc.php");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $user_id = $_SESSION['user_id'];

    // Query for transactions involving the current user (as sender or receiver)
    $transactionSql = "SELECT t.transaction_id, t.sender_id, t.receiver_id, t.status, t.item_id, i.item_name, 
                               us.first_name as sender_first_name, us.last_name as sender_last_name,
                               ur.first_name as receiver_first_name, ur.last_name as receiver_last_name
                        FROM transactions t
                        JOIN users us ON t.sender_id = us.user_id
                        JOIN users ur ON t.receiver_id = ur.user_id
                        JOIN lost_items i ON t.item_id = i.item_id
                        WHERE t.sender_id = '$user_id' OR t.receiver_id = '$user_id'";
    $transactionResult = $conn->query($transactionSql);

    // Close the database connection
    $conn->close();
    ?>

<div class="container mt-4">
    <h3 class="mb-3">Transactions</h3>
    <p class="mb-3"><b>Note:</b> <i>Ensure you refresh the page after claiming an item for the changes to reflect!</i>
    </p>
    <p class="mb-4"><strong>Warning:</strong> Only claim an item when it is in your possession. Verify that the receiver
        of an item claims it for security purposes!</p>

    <?php if ($transactionResult->num_rows > 0) { ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Item Name</th>
                    <th>Sender</th>
                    <th>Receiver</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($transactionRow = $transactionResult->fetch_assoc()) { ?>
                <tr>
                    <?php
                                $senderName = ($transactionRow['sender_id'] == $user_id) ? 'You' : $transactionRow['sender_first_name'] . ' ' . $transactionRow['sender_last_name'];
                                $receiverName = ($transactionRow['receiver_id'] == $user_id) ? 'You' : $transactionRow['receiver_first_name'] . ' ' . $transactionRow['receiver_last_name'];
                                ?>
                    <td><?php echo $transactionRow['item_name']; ?></td>
                    <td><?php echo $senderName; ?></td>
                    <td><?php echo $receiverName; ?></td>
                    <td><?php echo $transactionRow['status']; ?></td>
                    <td>
                        <?php
    // Display claim button for pending transactions to the user
    if ($transactionRow['status'] == 'pending' && $transactionRow['receiver_id'] == $user_id) {
    ?>
                        <button class="btn btn-success claim-button"
                            data-transaction-id="<?php echo $transactionRow['transaction_id']; ?>">Claim Item</button>
                        <?php } ?>

                        <?php
    // Display decline button for pending transactions to the user
    if ($transactionRow['status'] == 'pending' && $transactionRow['receiver_id'] == $user_id) {
    ?>
                        <button class="btn btn-danger decline-button"
                            data-transaction-id="<?php echo $transactionRow['transaction_id']; ?>">Decline</button>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php } else { ?>
    <p>You Have No transactions.</p>
    <?php } ?>
</div>

<script src="jquery.js"></script>
<script>
$(document).ready(function() {
    // Handle Claim button click event
    $(".claim-button").click(function() {
        var transactionId = $(this).data("transaction-id");
        var confirmation = confirm(
            "Are you sure the item is in your possession and you want to claim it?");

        if (confirmation) {
            // Make an AJAX request to claim_item.php
            $.post("claim_item.php", {
                transaction_id: transactionId
            }, function(response) {
                // Handle the response from the server
                if (response.success) {
                    alert("Item claimed successfully!");
                    // Optionally, you can reload the page to reflect the updated transactions
                    window.location.reload();
                } else {
                    alert("Claiming item failed!");
                }
            }, "json");
        }
    });

    // Handle Decline button click event
    $(".decline-button").click(function() {
        var transactionId = $(this).data("transaction-id");
        var confirmation = confirm(
            "Are you sure you want to reject this transaction?");

        if (confirmation) {
            // Make an AJAX request to reject_item.php
            $.post("reject_item.php", {
                transaction_id: transactionId
            }, function(response) {
                // Handle the response from the server
                if (response.success) {
                    alert("Transaction declined successfully!");
                    // Optionally, you can reload the page to reflect the updated transactions
                    window.location.reload();
                } else {
                    alert("Declining transaction failed!");
                }
            }, "json");
        }
    });
});
</script>



<?php
} else {
    include("includes/login_check.inc.php");
}

include("footer.php");
?>