<?php
session_start();
include("database.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $employeeID = $_POST['employee_id'];
    $password   = $_POST['password'];

    // Password default untuk semua employee
    if($password === "101"){
        // Cari employee dalam database ikut Employee_ID
        $query = "SELECT * FROM employee WHERE Employee_ID='$employeeID'";
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_assoc($result);
            // Simpan dalam session
            $_SESSION['employee_id']   = $row['Employee_ID'];
            $_SESSION['username']      = $row['Employee_Name']; // untuk display "Welcome, Abu"
            header("Location: employee.php");
            exit();
        } else {
            $error = "Employee ID tidak wujud!";
        }
    } else {
        $error = "Password salah! (Default: 101)";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post">
        <label>Employee ID:</label><br>
        <input type="text" name="employee_id" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
