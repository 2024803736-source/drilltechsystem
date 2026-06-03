<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: loginEM.php");
    exit();
}
include("database.php"); // sambung database
$result = mysqli_query($conn, "SELECT * FROM employee");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee Dashboard</title>
    <style>
        body {
            background-image: url('construction_bg.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            color: white;
            margin: 0;
        }
        .header {
            background-color: #006400;
            padding: 15px;
            font-size: 20px;
            color: white;
        }
        .sidebar {
            width: 200px;
            background-color: #006400;
            height: 100vh;
            position: fixed;
            padding-top: 30px;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 12px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #228B22;
        }
        .content {
            margin-left: 220px;
            padding: 20px;
        }
        .card {
            background-color: rgba(0, 100, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        h1, h2 {
            color: #FFD700;
        }
    </style>
</head>
<body>
    <div class="header">Welcome, <?php echo $_SESSION['username']; ?></div>

<div class="sidebar">
    <a href="employee.php">Dashboard</a>
    <a href="projectEM.php">Project</a>
    <a href="profileEM.php">Profile</a>
    <a href="payrollEM.php">Payroll</a>
</div>


    <div class="content">
        <div class="card">
            <h2>Active Projects</h2>
            
        </div>
        <div class="card">
            <h2>Completed Projects</h2>
            
        </div>
        <div class="card">
            <h2>Recent Updates</h2>
            
        </div>
    </div>
</body>
</html>
