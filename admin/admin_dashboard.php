<!-- admin_dashboard.php -->
<?php
include("header.php");

// Connect to your database (replace these with your actual database credentials)
include('../includes/dbh.inc.php');
include('dashboard_functions.php');

?>

<!-- Admin Dashboard Content -->
<div class="container mt-4">

    <!-- User Statistics -->
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="card-title">User Statistics</h3>
            <p class="card-text">Pending Verifications: <?php echo $userStatistics['pendingVerifications']; ?></p>
            <p class="card-text">Verified Users: <?php echo $userStatistics['verifiedUsers']; ?></p>
            <p class="card-text">Total Users: <?php echo $userStatistics['totalUsers']; ?></p>
            <a href="user_management.php" class="btn btn-primary">Manage Users</a>

        </div>
    </div>

    <!-- Lost and Found Items -->
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="card-title">Lost and Found Items</h3>
            <p class="card-text">Reported Lost Items: <?php echo $lostAndFoundStatistics['totalLostItems']; ?></p>
            <p class="card-text">Reported Found Items: <?php echo $lostAndFoundStatistics['totalFoundItems']; ?></p>
            <p class="card-text">Total Items: <?php echo $lostAndFoundStatistics['totalItems']; ?></p>
            <a href="lost_and_found.php" class="btn btn-primary">View All Items</a>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="card-title">Recent Activities</h3>
            <?php
            // Implement logic to fetch and display recent activities from the database
            // You can use a similar approach as in previous examples
            ?>
        </div>
    </div>

    <!-- System Health -->
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="card-title">System Health</h3>
            <p class="card-text">System Status: <?php echo $systemHealth['systemStatus']; ?></p>
            <p class="card-text">System Issues: <?php echo $systemHealth['systemIssues']; ?></p>
        </div>
    </div>

    <!-- Other sections... -->

</div>

<?php include("footer.php"); ?>