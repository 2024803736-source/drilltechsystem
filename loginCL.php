<?php
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $_SESSION['client_id'] = "C001"; 
    $_SESSION['client_name'] = "Demo Client"; 
    header("Location: client.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Client Login</title>
</head>
<body>
    <h2>Login Client</h2>
    <form method="post">
        <label>Client ID:</label><br>
        <input type="text" name="username" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
