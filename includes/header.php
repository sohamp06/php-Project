<?php

/*
    Name: Soham Patel
    Date: September 13, 2023
    Course Code: INFT2100
*/

session_start();

require("./includes/constants.php");
require("./includes/db.php");
require("./includes/functions.php");

ob_start();
?>



<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">

    <!--
        Name: <?php echo $name . "\n"; ?>
        File: <?php echo $file . "\n"; ?>
        Date: <?php echo $date . "\n"; ?>
    -->

    <title><?php echo $title ?></title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./css/styles.css" rel="stylesheet">

</head>

<body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">S & P Co.</a>
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <?php

                if (isset($_SESSION['user'])) {
                    echo '<a class="nav-link" href="./logout.php">Sign out</a>';
                } else {
                    echo '<a class="nav-link" href="./logout.php">Sign in</a>';
                }
                ?>



            </li>
        </ul>
    </nav>
    <?php
    if (isset($_SESSION['user'])) {
        echo 
            '<a class="nav-link active" href="./dashboard.php" style="color: blue;">' . 
            '<span data-feather="home"></span>' .
            'Dashboard <span class="sr-only">(current)</span>' .
            '</a>' ;
    }
    
    if (isset($_SESSION['user']) && $_SESSION['user']['usertype'] == ADMIN) {
        echo
            '<a class="nav-link active" href="./salespeople.php" style="color: blue;">' . 
            '<span data-feather="home"></span>' .
            'Salesperson Registration <span class="sr-only">(current)</span>' .
            '</a>' ;
    }
    if (isset($_SESSION['user'])) {
        echo 
            '<a class="nav-link active" href="./clients.php" style="color: blue;">' . 
            '<span data-feather="home"></span>' .
            'Client Registration <span class="sr-only">(current)</span>' .
            '</a>';
    }
    
    
    ?>

    <main class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">