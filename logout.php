<?php
    include "./includes/header.php";

    $user =$_SESSION["user"];
    $email_address = $user['emailaddress'];
    session_unset();

    session_destroy();

    session_start();
    
    ob_start();
    $_SESSION['message'] = "You successfully logged out";
    write_to_log("Sign out","success",$email_address);
    header("Location:sign-in.php");

    ob_flush();

?>