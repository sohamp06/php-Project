<?php
/*
    Name: Soham Patel
    Date: September 13, 2023
    Course Code: INFT2100
*/
?>
<?php
$title = "Clients Page";
$name = "Soham Patel";
$file = "clients.php";
$date = "9/13/2023";
include "./includes/header.php";

// Redirect to sign-in page if the user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location: sign-in.php");
    ob_flush();
}
// Fetch salespeople for the select dropdown
$sql = "SELECT id, emailaddress FROM users";
$result = pg_query(db_connect(), $sql);

$salespeople = [];
while ($data = pg_fetch_assoc($result)) {
    $salespeople[$data['id']] = $data['emailaddress'];
}
$fname = "";
$lname = "";
$email = "";
$phone = "";
$extension = "";
$sales_id = 5000;
$error = "";
$message = "";
$logo_name = "";
$logo_destination = "";

global $conn;

// Fetch salespeople for the select dropdown
$sql = "SELECT emailaddress FROM users";
$result = pg_query(db_connect(), $sql);
$salespeople = [];
while ($data = pg_fetch_row($result)) {
    foreach ($data as $val) {
        $salespeople[] = $val;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
    $fname = trim($_POST["inputFName"]);
    $lname = trim($_POST["inputLName"]);
    $email = trim($_POST["inputEmail"]);
    $extension = trim($_POST["inputExtension"]);
    $phone = trim($_POST["inputPhone"]);
    $sales_id = trim($_POST["inputSalesPerson"]);

    // Validate form inputs
    if (empty($fname)) {
        $error .= "You must enter your First Name.</br>";
    }
    if (empty($lname)) {
        $error .= "You must enter your Last Name.</br>";
    }
    if (empty($email)) {
        $error .= "You must enter Your Email Address.</br>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error .= "<em>" . $email . "</em> is not a valid email address.</br>";
        $email = "";
    } elseif (user_select($email)) {
        $error .= "This email (" . $email . ") already exists for a client.</br>";
    }

    // File upload logic
    if (isset($_FILES["uploadFile"]) && $_FILES["uploadFile"]["error"] === UPLOAD_ERR_OK) {
        $allowedExtensions = array("jpeg", "jpg", "gif");
        $fileExtension = strtolower(pathinfo($_FILES["uploadFile"]["name"], PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedExtensions)) {
            $logo_destination = "./logo/";
            $logo_name = $logo_destination . basename($_FILES["uploadFile"]["name"]);
            $logo_temp_location = $_FILES["uploadFile"]["tmp_name"];

            if (move_uploaded_file($logo_temp_location, $logo_name)) {
                $message .= "File successfully uploaded</br>";
            } else {
                $error .= "File upload failed!</br>";
            }
        } else {
            $error .= "Only .jpeg, .jpg, and .gif files are allowed for upload.</br>";
        }
    }

   // Proceed with the insertion only if there are no errors
   if (empty($error)) {
    // Get the correct sales_id based on the current user
    if (isset($_SESSION['user']) && ($_SESSION['user']['usertype'] == SALES)) {
        $sales_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;
       
    }

    // Proceed with the insertion
    if (insert_client($email, $fname, $lname, $phone, $logo_name, $extension, $sales_id)) {
        // Add a success message
        $message = "Client registered successfully.";
    } else {
        $error .= "Something went wrong with the insert.";
    }
}
}

?>
<div class="container"> 
    <div class="row">
        <div class="col-md-6 offset-md-3"> 

            <h3><?php echo $error; ?></h3>
            <h3><?php echo $message; ?></h3>

            <?php
            $client_form = array(
                array(
                    "type" => "text",
                    "name" => "inputFName",
                    "value" => $fname,
                    "label" => "First Name"
                ),
                array(
                    "type" => "text",
                    "name" => "inputLName",
                    "value" => $lname,
                    "label" => "Last Name"
                ),
                array(
                    "type" => "text",
                    "name" => "inputEmail",
                    "value" => $email,
                    "label" => "Email Address"
                ),
                array(
                    "type" => "text",
                    "name" => "inputPhone",
                    "value" => $phone,
                    "label" => "Phone Number"
                ),
                array(
                    "type" => "text",
                    "name" => "inputExtension",
                    "value" => $extension,
                    "label" => "Extension"
                ), 
            
                array(
                    "type" => "select",
                    "name" => "inputSalesPerson",
                    "label" => "Select Salesperson",
                    "options" => $salespeople,
                    "value" => $sales_id
                ),
                array(
                    "type" => "file",
                    "id" => "uploadFile",
                    "name" => "uploadFile",
                    "value" => $logo_name,
                    "label" => "Select file to upload"
                ),
                array(
                    "type" => "submit",
                    "name" => "",
                    "value" => "",
                    "label" => "Register"
                ),
                array(
                    "type" => "reset",
                    "name" => "",
                    "value" => "",
                    "label" => "Reset"
                ),
            );

            echo '<form method="post" enctype="multipart/form-data">';

            foreach ($client_form as $field) {
                if ($field['type'] !== 'submit' && $field['type'] !== 'reset') {
                    echo '<div class="form-group">';
                    echo '<label for="' . $field['name'] . '">' . $field['label'] . '</label>';
                    echo '<input type="' . $field['type'] . '" class="form-control" id="' . $field['name'] . '" name="' . $field['name'] . '" value="' . $field['value'] . '">';
                    echo '</div>';
                } else {
                    echo '<input type="' . $field['type'] . '" class="btn btn-primary" value="' . $field['label'] . '">';
                }
            }

            echo '</form>';
            
            $page = isset($_GET['page']) ? $_GET['page'] : 1;

            if (isset($_SESSION['user']) && ($_SESSION['user']['usertype'] == ADMIN)) {
                display_table(
                    array(
                        "id" => "ID",
                        "emailaddress" => "Email Address",
                        "firstname" => "First Name",
                        "lastname" => "Last Name",
                        "phonenumber" => "Phone Number",
                        "extension" => "Extension",
                        "logopath" => "Logo",
                        "salesemail" => "Sales Email"
                    ),
                    clients_select_all($page),
                    clients_count(),
                    $page
                );
            } elseif (isset($_SESSION['user']) && ($_SESSION['user']['usertype'] == SALES)) {
                $id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;

                display_table(
                    array(
                        "id" => "ID",
                        "emailaddress" => "Email Address",
                        "firstname" => "First Name",
                        "lastname" => "Last Name",
                        "phonenumber" => "Phone Number",
                        "extension" => "Extension",
                        "logopath" => "Logo",
                        "salesemail" => "Sales Email"
                    ),
                    clients_select_by_salesperson($page, $id),
                    clients_count_by_salesperson($id),
                    $page
                );
            }
            ?>            
        </div>
    </div>
</div>

<?php include "./includes/footer.php"; ?>