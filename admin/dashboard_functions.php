<?php

// Connect to your database (replace these with your actual database credentials)
include('../includes/dbh.inc.php');

// Function to fetch user statistics
function fetchUserStatistics($conn)
{
    $totalUsers = 0;
    $verifiedUsers = 0;
    $pendingVerifications = 0;

    // Fetch total users
    $sqlTotalUsers = "SELECT COUNT(*) AS total_users FROM users";
    $resultTotalUsers = $conn->query($sqlTotalUsers);

    if ($resultTotalUsers->num_rows > 0) {
        $rowTotalUsers = $resultTotalUsers->fetch_assoc();
        $totalUsers = $rowTotalUsers['total_users'];
    }

    // Fetch verified users
    $sqlVerifiedUsers = "SELECT COUNT(*) AS verified_users FROM users WHERE verification_status = 'verified'";
    $resultVerifiedUsers = $conn->query($sqlVerifiedUsers);

    if ($resultVerifiedUsers->num_rows > 0) {
        $rowVerifiedUsers = $resultVerifiedUsers->fetch_assoc();
        $verifiedUsers = $rowVerifiedUsers['verified_users'];
    }

    // Fetch pending verifications
    $sqlPendingVerifications = "SELECT COUNT(*) AS pending_verifications FROM users WHERE verification_status = 'pending'";
    $resultPendingVerifications = $conn->query($sqlPendingVerifications);

    if ($resultPendingVerifications->num_rows > 0) {
        $rowPendingVerifications = $resultPendingVerifications->fetch_assoc();
        $pendingVerifications = $rowPendingVerifications['pending_verifications'];
    }

    return array('totalUsers' => $totalUsers, 'verifiedUsers' => $verifiedUsers, 'pendingVerifications' => $pendingVerifications);
}

// Fetch user statistics
$userStatistics = fetchUserStatistics($conn);

// Function to fetch lost and found items statistics
function fetchLostAndFoundStatistics($conn)
{
    $totalItems = 0;
    $totalLostItems = 0;
    $totalFoundItems = 0;

    // Fetch total items
    $sqlTotalItems = "SELECT COUNT(*) AS total_items FROM lost_items";
    $resultTotalItems = $conn->query($sqlTotalItems);

    if ($resultTotalItems->num_rows > 0) {
        $rowTotalItems = $resultTotalItems->fetch_assoc();
        $totalItems = $rowTotalItems['total_items'];
    }

    // Fetch total lost items
    $sqlTotalLostItems = "SELECT COUNT(*) AS total_lost_items FROM lost_items WHERE status = 'lost'";
    $resultTotalLostItems = $conn->query($sqlTotalLostItems);

    if ($resultTotalLostItems->num_rows > 0) {
        $rowTotalLostItems = $resultTotalLostItems->fetch_assoc();
        $totalLostItems = $rowTotalLostItems['total_lost_items'];
    }

    // Fetch total found items
    $sqlTotalFoundItems = "SELECT COUNT(*) AS total_found_items FROM lost_items WHERE status = 'found'";
    $resultTotalFoundItems = $conn->query($sqlTotalFoundItems);

    if ($resultTotalFoundItems->num_rows > 0) {
        $rowTotalFoundItems = $resultTotalFoundItems->fetch_assoc();
        $totalFoundItems = $rowTotalFoundItems['total_found_items'];
    }

    return array('totalItems' => $totalItems, 'totalLostItems' => $totalLostItems, 'totalFoundItems' => $totalFoundItems);
}

// Function to fetch system health information
function fetchSystemHealth($conn)
{
    $systemStatus = 'Online';
    $systemIssues = 'No reported issues';

    // You can add more complex logic to determine the system status and issues

    return array('systemStatus' => $systemStatus, 'systemIssues' => $systemIssues);
}

// Fetch lost and found items statistics
$lostAndFoundStatistics = fetchLostAndFoundStatistics($conn);

// Fetch system health information
$systemHealth = fetchSystemHealth($conn);

// Close the database connection
$conn->close();
?>