<?php
session_start();
include("database.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // terus masuk dashboard tanpa semak database
    $_SESSION['username'] = "Abu"; // nama sementara
    header("Location: employee.php");
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post">
        <label>Username:</label><br>
        <input type="text" name="username"><br>
        <label>Password:</label><br>
        <input type="password" name="password"><br><br>
        <input type="submit" value="Login">
    </form>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
