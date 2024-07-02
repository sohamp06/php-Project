<?php
$title = "Sign-in page";
$name = "Soham Patel";
$file = "sign-in.php";
$date = "9/14/2023";
include "./includes/header.php";


if (isset($_SESSION['user'])) {
    header("Location:dashboard.php");
    ob_flush();
}

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
} else {
    $message = "";
}




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_address = trim($_POST['inputEmail']);
    $password = trim($_POST['inputPassword']);
   
    //email validation
    if (!isset($email_address) || strlen($email_address) == 0) {
        $message .= "YOU MUST ENTER  YOUR EMAIL ADDRESS</br>";
    } else if (!filter_var($email_address . FILTER_VALIDATE_EMAIL)) {
        $message .= "<em>" . $email_address . "</em> is not a valid email address";
    }

    //password validation
    if (!isset($password) || strlen($password) == 0) {
        $message .= "You must Enter your Password<br/>";
    }

    if ($message == "") {


        $results = pg_execute($conn, "user_retrieve", array($email_address));

       
        if (pg_num_rows($results) == 1) {
            $user = pg_fetch_assoc($results, 0);

            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                $_SESSION['message'] = "You successfully logged in";
                $now = date("Y:m:d G:i:s");

                
                $sql = "UPDATE users SET LastLoggedIn='" . $now . "' WHERE EmailAddress='" . $email_address . "'";
                $results = pg_query($conn, $sql);
                write_to_log("Sign in", "success", $email_address);
                header("Location:dashboard.php");
                ob_flush();
            }

           
        } else {
            write_to_log("Sign in", "failed", $email_address);
            $message = "User name or password not valid !!";
        }
   
    }
}

?>
<h2>
    <?php
        echo $message;
    ?>
</h2>
<form class="form-signin" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
    <label for="inputEmail" class="sr-only">Email address</label>
    <input type="text" name="inputEmail" id="inputEmail" class="form-control" placeholder="Email address" autofocus>
    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" name="inputPassword" id="inputPassword" class="form-control" placeholder="Password">
    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
</form>

<?php
include "./includes/footer.php";
?>