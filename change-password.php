
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
</head>
<body>
    <h2>Change Password</h2>

    <?php
  
    if (isset($error)) {
        echo "$error</p>";
    }
    ?>

    <form method="POST" action="password.php">
        <label for="password">New Password:</label>
        <input type="password" name="password" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" required>

        <input type="submit" value="Change Password">
    </form>
</body>
</html>
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (strlen($password) < 3) {
        $error = "Password must have 3 characters.";
    } elseif ($password !== $confirmPassword) {
        $error = "Wrong password.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $userId = $_SESSION['user_id']; 
        $updateQuery = "UPDATE users SET password = :hashed_password WHERE id = :EmailAddress";

        header("Location: dashboard.php?success=Password updated successfully");
        exit();
    }
}
?>
