<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website Title</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/navbar.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="assets/js/my_js/js.js"></script>
    <script src="assets/js/my_js/fullscreen.js"></script>
    
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
        <?php if (isset($_SESSION["user_id"])) { 
            $user_id = $_SESSION["user_id"];?>
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark" aria-label="Third navbar example">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Lost And Found</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample03" aria-controls="navbarsExample03" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarsExample03">
                    <ul class="navbar-nav me-auto mb-2 mb-sm-0">
                        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'home.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="home.php">Home</a>
                        </li>
                        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'lost_and_found.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="lost_and_found.php">Lost And Found</a>
                        </li>
                        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'report_item.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="report_item.php">Report An Item</a>
                        </li>
                        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'messages.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="messages.php">Messages</a>
                        </li>
                        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'transactions.php') ? 'active' : ''; ?>">
                            <a class="nav-link" href="transactions.php">Transactions</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-toggle="dropdown" aria-expanded="false">More</a>
                            <ul class="dropdown-menu" aria-labelledby="dropdown03">
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <li><a class="dropdown-item" href="includes/logout.inc.php" id="logout-link">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <?php } else { ?>
        <header>
            <h2>Federal University Wukari Lost and Found</h2>
        </header>
        <?php } ?>
    </main>
