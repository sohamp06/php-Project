<?php

/*
    Name: Soham Patel
    Date: September 13, 2023
    Course Code: INFT2100
*/
?>
<?php
$title = "Calls Page";
$name = "Soham Patel";
$file = "calls.php";
$date = "9/13/2023";
include "./includes/header.php";

if (!isset($_SESSION['user'])) {
    header("Location: sign-in.php");
    ob_flush();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clientName = trim($_POST["clientName"]);
    $callTime = trim($_POST["callTime"]);
    
    if (empty($clientName) || empty($callTime)) {
        $error = "Both client name and call time are required fields.";
    } else {
       
        $conn = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass");
        
        if (!$conn) {
            die("Database connection failed");
        }
        
        $query = "INSERT INTO calls (time_of_call, notes, clients_id) VALUES ($1, $2, $3)";
        
        $result = pg_query_params($conn, $query, array($callTime, 'Call notes', $clientName));
        
        if ($result) {
            $message = "Call record successfully created";
            $clientName = "";
            $callTime = "";
        } else {
            $error = "Failed to insert the call record.";
        }
    }
}

$clientName = "";
$callTime = "";

$clientNameInput = array(
    "type" => "text",
    "name" => "clientName",
    "value" => $clientName,
    "label" => "Client Name"
);

$callTimeInput = array(
    "type" => "datetime-local",
    "name" => "callTime",
    "value" => $callTime,
    "label" => "Call Time"
);

$callForm = array(
    $clientNameInput,
    $callTimeInput,
    array(
        "type" => "submit",
        "name" => "",
        "value" => "",
        "label" => "Create Call Record"
    )
);

if ($_SESSION['user']['userType'] == 'SALESPERSON') {
    $salespersonIDInput = array(
        "type" => "hidden",
        "name" => "salespersonID",
        "value" => $_SESSION['user']['userID']
    );
    $callForm[] = $salespersonIDInput;
}

display_form($client_form);

include "./includes/footer.php";
?>
