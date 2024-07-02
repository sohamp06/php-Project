<?php

$title = "Home-Page";
$name = "Soham Patel";
$file = "salespeople.php";
$date = "9/13/2023";
include "./includes/header.php";
?>


<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <?php
            if (isset($_SESSION['user']) && is_array($_SESSION['user']) && isset($_SESSION['user']['userType']) && $_SESSION['user']['userType'] != ADMIN) {

                header("Location: sign-in.php");
                ob_flush();
            }

            $email_address = "";
            $fname = "";
            $lname = "";
            $password1 = "";
            $password2 = "";
            $phone = "";
            $message = "";
            $error = "";


            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST["inputFName"])) {
                    $fname = trim($_POST["inputFName"]);
                }
                if (isset($_POST["inputLName"])) {
                    $lname = trim($_POST["inputLName"]);
                }

                if (isset($_POST["inputEmail"])) {
                    $email_address = trim($_POST["inputEmail"]);
                }
                if (isset($_POST["inputPassword1"])) {
                    $password1 = trim($_POST["inputPassword1"]);
                }
                if (isset($_POST["inputPassword2"])) {
                    $password2 = trim($_POST["inputPassword2"]);
                }
                if (isset($_POST["inputPhone"])) {
                    $phone = trim($_POST["inputPhone"]);
                }

                if (empty($fname)) {
                    $error .= "You must enter Your First Name.<br>";
                }
                if (empty($lname)) {
                    $error .= "You must enter Your Last Name.<br>";
                }
                if (empty($email_address)) {
                    $error .= "You must enter Your Email.<br>";
                }
                if (empty($password1)) {
                    $error .= "You must enter Your Password.<br>";
                } elseif (strcmp($password1, $password2) !== 0) {
                    $error .= "Submitted password and confirm password should be the same.";
                }

                if (empty($error)) {
                    if (insert_salesperson($email_address, $password1, $fname, $lname, $phone, SALES)) {
                        $message = "You have successfully registered the salesperson";
                        $email_address = "";
                        $fname = "";
                        $lname = "";
                        $phone = "";
                    } else {
                        $error .= "Something went wrong with the insert.";
                    }
                } else {
                    $error .= "<br>Please try again.";
                    $message .= $error;
                }
                if (isset($_POST['active']) && is_array($_POST['active'])) {
                    foreach ($_POST['active'] as $id => $status) {
                        $_SESSION['selected_status'][$id] = $status;
                    }
                }
            }
            ?>

            <h3>
                <?php
                if (!empty($message)) {
                    echo $message;
                } else {
                    echo $error;
                }
                ?>
            </h3>
            <form method="POST" action="salespeople.php">
                <?php
                $user_form = array(
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
                        "value" => $email_address,
                        "label" => "Email Address"
                    ),
                    array(
                        "type" => "password",
                        "name" => "inputPassword1",
                        "value" => "",
                        "label" => "Password"
                    ),
                    array(
                        "type" => "password",
                        "name" => "inputPassword2",
                        "value" => "",
                        "label" => "Confirm Password"
                    ),
                    array(
                        "type" => "text",
                        "name" => "inputPhone",
                        "value" => $phone,
                        "label" => "Phone Number"
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
                display_form($user_form);
                $page = 1;
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                }
                $salesPersonData = array(
                    "id" => "ID",
                    "emailaddress" => "Email Address",
                    "firstname" => "First Name",
                    "lastname" => "Last Name",
                    "phoneextension" => "Phone Number",
                    "usertype" => "User Type",
                );

                $salespeoplesValues = salesperson_select_all($page);
                $salespeopleNumbers = salesperson_count();

                ?>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <?php
                                foreach ($salesPersonData as $key => $value) {
                                    echo '<th scope="col">' . $value . '</th>';
                                }
                                ?>
                                <th scope="col">Is Active?</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($salespeoplesValues as $salesperson) {
                                echo '<tr>';
                                foreach ($salesPersonData as $key => $value) {
                                    echo '<td>' . $salesperson[$key] . '</td>';
                                }

                                echo '<td>';
                                echo '<div class="form-check">';
                                echo '<input type="radio" id="' . $salesperson['id'] . '-Active" name="active[' . $salesperson['id'] . ']" value="Active" class="form-check-input" ' . (isset($salesperson['isactive']) && $salesperson['isactive'] === 'Active' ? 'checked' : '') . '>';
                                echo '<label class="form-check-label" for="' . $salesperson['id'] . '-Active">Active</label>';
                                echo '</div>';

                                echo '<div class="form-check">';
                                echo '<input type="radio" id="' . $salesperson['id'] . '-Inactive" name="active[' . $salesperson['id'] . ']" value="Inactive" class="form-check-input" ' . (isset($salesperson['isactive']) && $salesperson['isactive'] === 'Inactive' ? 'checked' : '') . '>';
                                echo '<label class="form-check-label" for="' . $salesperson['id'] . '-Inactive">Inactive</label>';
                                echo '</div>';
                                echo '<input type="submit" value="Update" class="btn btn-primary" />';
                                echo '</td>';
                            }
                            ?>


                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item"><a class="page-link" href="<?php echo $_SERVER['PHP_SELF'] . '?page=' . (($page > 1) ? $page - 1 : $page); ?>">Previous</a></li>
                            <?php
                            for ($i = 0; $i < $salespeopleNumbers / RECORDS; $i++) {
                                echo '<li class="page-item"><a class="page-link" href="' . $_SERVER['PHP_SELF'] . '?page=' . ($i + 1) . '">' . ($i + 1) . '</a></li>';
                            }
                            ?>
                            <li class="page-item"><a class="page-link" href="<?php echo $_SERVER['PHP_SELF'] . '?page=' . (($page < $salespeopleNumbers / RECORDS) ? $page + 1 : $page); ?>">Next</a></li>
                        </ul>
                    </nav>
                </div>
        </div>

        <?php
        include "./includes/footer.php";
        ?>