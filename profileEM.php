<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: loginEM.php");
    exit();
}
include("database.php");

$employeeID = $_SESSION['employee_id'];

// Ambil data employee dari DB
$result = mysqli_query($conn, "SELECT * FROM employee WHERE Employee_ID='$employeeID'");
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profile Dashboard</title>
    <style>
        body {
            background-image: url('images/construction_bg.jpg');
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
        .profile-card {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            margin: auto;
            text-align: left;
        }
        h2 {
            color: #FFD700;
        }
        .button-container {
            margin-top: 20px;
            text-align: center;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-weight: bold;
        }
        .update-btn {
            background-color: #32CD32;
            color: white;
            margin-right: 10px;
        }
        .edit-btn {
            background-color: #1E90FF;
            color: white;
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
        <div class="profile-card">
    <h2>Personal Details</h2>
    
    <!-- Slot gambar profile -->
    <div style="text-align:center; margin-bottom:15px;">
        <img src="images/gambar/<?php echo $employeeID; ?>.jpg" 
             alt="Profile Picture" 
             style="width:200px;height:200px;border-radius:50%;border:5px solid #006400;">
    </div>

    <p><strong>Name:</strong> <?php echo $row['Employee_Name']; ?></p>
    <p><strong>Contact:</strong> <?php echo $row['Employee_Contact']; ?></p>
    <p><strong>Gender:</strong> <?php echo $row['Employee_Gender']; ?></p>
    <p><strong>Position:</strong> <?php echo $row['Employee_Position']; ?></p>
    <p><strong>Address:</strong> <?php echo $row['Employee_Address']; ?></p>
</div>


        <div class="button-container">
            <button class="btn edit-btn" onclick="window.location.href='editProfileEM.php'">Edit Personal Details</button>
        </div>
    </div>
</body>
</html>
