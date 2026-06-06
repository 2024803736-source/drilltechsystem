<?php
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){
   
    $adminID = $_POST['username'];
    $password = $_POST['password'];

    if($adminID === "01" && $password === "123"){
        $_SESSION['admin_id'] = "A001"; 
        $_SESSION['admin_name'] = "Demo Admin"; 
        header("Location: admin.php");
        exit();
    } else {
        $error = "ID atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>
    <h2>Login Admin</h2>
    <form method="post">
        <label>Admin ID:</label><br>
        <input type="text" name="username" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
