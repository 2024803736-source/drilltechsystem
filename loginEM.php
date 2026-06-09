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
            $_SESSION['username']      = $row['Employee_Name']; 
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Login - DrillTech HDD</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.75)), 
                        url('images/construction_bg.jpg') center/cover no-repeat fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .login-box {
            background: rgba(0, 77, 0, 0.95);
            padding: 40px 35px;
            border-radius: 16px;
            width: 420px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.6);
        }
        .logo {
            font-size: 52px;
            margin-bottom: 8px;
        }
        h1 { font-size: 26px; margin-bottom: 6px; }
        p.subtitle { margin-bottom: 30px; opacity: 0.9; font-size: 15px; }
        
        input {
            width: 100%;
            padding: 14px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            background: white;
            color: #333;
        }
        .btn {
            width: 100%;
            padding: 14px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn:hover { background: #218838; }
        .error { color: #ff6b6b; margin-top: 15px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="logo">👷</div>
        <h1>DRILLTECH HDD</h1>
        <p class="subtitle">Employee Portal</p>

        <form method="post">
            <input type="text" name="employee_id" placeholder="Employee ID" required>
            <input type="password" name="password" placeholder="Password (Default: 101)" required>
            <button type="submit" class="btn">LOG IN</button>
        </form>

        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    </div>
</body>
</html>
