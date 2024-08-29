<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charse t="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website Title</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/navbar.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/my_js/js.js"></script>
    <script src="../assets/js/my_js/fullscreen.js"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
    .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
    }

    @media (min-width: 768px) {
        .bd-placeholder-img-lg {
            font-size: 3.5rem;
        }
    }
    </style>
</head>

<body>

    <main>
        <?php if (isset($_SESSION["user_id"])) { ?>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark" aria-label="Fourth navbar example">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Lost And Found</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarsExample04">
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li
                            class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
                        </li>
                        <li
                            class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'lost_and_found.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="lost_and_found.php">Lost And Found</a>
                        </li>
                        <li
                            class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'report_item.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="report_item.php">Report An Item</a>
                        </li>
                        <li
                            class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'messages.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="messages.php">Messages</a>
                        </li>
                        <li
                            class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'user_management.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="user_management.php">User Management</a>
                        </li>
                        <li
                            class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'user_verification.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="user_verification.php">User Verification</a>
                        </li>
                        <li
                            class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'item_management.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="item_management.php">Item Management</a>
                        </li>
                        <li
                            class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'transactions.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="transactions.php">Transactions</a>
                        </li>

                        <li
                            class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'feedback.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="feedback.php">Feedback</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-bs-toggle="dropdown"
                                aria-expanded="false">More</a>
                            <ul class="dropdown-menu" aria-labelledby="dropdown04">
                                <?php
                                        $user_id = $_SESSION["user_id"];
                                        echo '<li><a class="dropdown-item" href="profile.php">Profile</a></li>';
                                        echo '<li><a class="dropdown-item" href="../includes/logout.inc.php" id="logout-link">Logout</a></li>';
                                    ?>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <?php
            } else {
        ?>
        <header>
            <h2 class='jumbotron'>
                Federal University Wukari Lost and Found
            </h2>
        </header>
        </div class='container-fluid'>
        <?php } ?>