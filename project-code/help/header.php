<!-- header.php -->
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website Title</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/navbar.css" rel="stylesheet">
    <script src="../assets/js/jquery.js"></script>
    <!-- <script src="../assets/js/bootstrap.js"></script> This line stops the hamburger menu from resizing -->
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/my_js/js.js"></script>
    <script src="../assets/js/my_js/fullscreen.js"></script>

    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Include jQuery and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

    <!-- Include Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>



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
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarsExample03" aria-controls="navbarsExample03" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>
        <?php
                                } else {                            
                                ?>
        <header>
            <h2>
                <?php echo "Federal University Wukari Lost and Found"; } ?>
            </h2>
            <!-- </nav> -->
        </header>
        </div class='container-fluid'>