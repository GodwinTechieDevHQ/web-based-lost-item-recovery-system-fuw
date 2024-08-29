<?php
include("header.php"); // Include admin header
include('../includes/dbh.inc.php');
?>

<div class="admin-content">
    <h2 class="jumbotron text-center">Feedback</h2>

    <!-- Display Feedback -->
    <?php
    $feedbackQuery = "SELECT * FROM feedback";
    $feedbackResult = $conn->query($feedbackQuery);
    $serialNumber = 1; // Initialize serial number
    ?>

    <?php if ($feedbackResult->num_rows > 0) : ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-sm">
            <thead class="thead-dark">
                <tr>
                    <th>Serial Number</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($feedback = $feedbackResult->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $serialNumber++; ?></td> <!-- Increment serial number for each row -->
                    <td><?php echo $feedback['name']; ?></td>
                    <td><?php echo $feedback['email']; ?></td>
                    <td><?php echo $feedback['feedback_type']; ?></td>
                    <td><?php echo $feedback['message']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else : ?>
    <p>No feedback found.</p>
    <?php endif; ?>
</div>

<?php
include("footer.php"); // Include admin footer
?>