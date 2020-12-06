<?php
 $root = $_SERVER['DOCUMENT_ROOT'];
 session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.6.3/dist/css/foundation.min.css"
        integrity="sha256-ogmFxjqiTMnZhxCqVmcqTvjfe1Y/ec4WaRj/aQPvn+I=" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php $root?>/N01348498_PhpProject/css/main.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.6.3/dist/js/foundation.min.js"
        integrity="sha256-pRF3zifJRA9jXGv++b06qwtSqX1byFQOLjqa2PTEb2o=" crossorigin="anonymous"></script>
   
    <title>Car Rental System Management Application</title>
</head>
<body>
    <div class="top-bar">
        <div class="top-bar-left">
            <ul class="dropdown menu" data-dropdown-menu>
                <li class="menu-text">CRMS</li>
                <li><a href="<?php $root?>/N01348498_PhpProject/views/home.php">Home</a></li>
                <li><a href="<?php $root?>/N01348498_PhpProject/views/cars/cars.php">Cars</a></li>
                <li><a href="<?php $root?>/N01348498_PhpProject/views/reservations/reservations.php">Reservations</a></li>
                <li><a href="<?php $root?>/N01348498_PhpProject/views/pattern.php">File Pattern</a></li>
            </ul>
        </div>
        <div class="top-bar-right">
            <ul class="dropdown menu" data-dropdown-menu>
                <?php                    
                    if (isset($_SESSION["email"] )){
                        echo '<li style="padding: .4rem 1rem;">Hello,' .$_SESSION["name"].'</li>';
                    }                
                ?>
                <li><a href="<?php $root?>/N01348498_PhpProject/views/register.php">Register</a></li>
                <li><a href="<?php $root?>/N01348498_PhpProject/views/login.php">Login</a></li>
                <li><a href="<?php $root?>/N01348498_PhpProject/views/logout.php">Logout</a></li>
                <li><a href="<?php $root?>/N01348498_PhpProject/views/resetPassword.php">Reset Password</a></li>
                
            </ul>
        </div>
    </div><br>

    