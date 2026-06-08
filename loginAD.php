<?php
session_start();
include("database.php"); // sambung ke database

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $adminID  = $_POST['admin_id'];
    $password = $_POST['password'];

    // Cari admin dalam database ikut Admin_ID
    $query  = "SELECT * FROM admin WHERE Admin_ID='$adminID' AND Admin_Password='$password'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);
        // Simpan dalam session
        $_SESSION['admin_id']   = $row['Admin_ID'];
        $_SESSION['admin_name'] = "Admin " . $row['Admin_ID']; // boleh tukar ikut column Admin_Name kalau ada
        header("Location: admin.php"); // redirect ke dashboard
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
        <input type="text" name="admin_id" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
