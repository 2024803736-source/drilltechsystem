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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - DrillTech HDD</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.65)), url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: white;
            min-height: 100vh;
        }
        .header {
            background:#004d00;;
            padding:15px 30px;
            display:flex; align-items:center; justify-content:space-between;
        }
        .logo { font-size:26px; font-weight:bold; }
        .sidebar {
            width:240px; background:#004d00;; position:fixed; height:100vh; padding-top:20px;
        }
        .sidebar a {
            display:flex; align-items:center; padding:15px 25px; color:white; text-decoration:none; gap:10px;
        }
        .sidebar a:hover, .sidebar a.active { background:#ff8c00; }
        .content { margin-left:260px; padding:30px; }
        .main-box {
            background:rgba(0,0,0,0.8); border-radius:12px; padding:25px; width:500px; margin:auto;
        }
        h2 { color:#ffcc00; margin-bottom:20px; text-align:center; }
        .profile-img { text-align:center; margin-bottom:20px; }
        .profile-img img {
            width:200px; height:200px; border-radius:50%; border:5px solid #004d00;
        }
        p { margin:10px 0; font-size:16px; }
        .button-container { text-align:center; margin-top:20px; }
        .btn {
            padding:10px 20px; border:none; border-radius:20px; cursor:pointer; font-weight:bold;
        }
        .edit-btn { background:#1E90FF; color:white; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">👷 DRILLTECH</div>
        <div>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></div>
    </div>

    <div class="sidebar">
        <a href="employee.php">📊 DASHBOARD</a>
        <a href="projectEM.php">🔍 PROJECT</a>
        <a href="profileEM.php" class="active">👤 PROFILE</a>
        <a href="payrollEM.php">💰 PAYROLL</a>
    </div>

    <div class="content">
        <div class="main-box">
            <h2>Personal Details</h2>
            <div class="profile-img">
                <img src="images/gambar/<?php echo $employeeID; ?>.jpg" alt="Profile Picture">
            </div>
            <p><strong>Name:</strong> <?php echo $row['Employee_Name']; ?></p>
            <p><strong>Contact:</strong> <?php echo $row['Employee_Contact']; ?></p>
            <p><strong>Gender:</strong> <?php echo $row['Employee_Gender']; ?></p>
            <p><strong>Position:</strong> <?php echo $row['Employee_Position']; ?></p>
            <p><strong>Address:</strong> <?php echo $row['Employee_Address']; ?></p>

            <div class="button-container">
                <button class="btn edit-btn" onclick="window.location.href='editProfileEM.php'">Edit Personal Details</button>
            </div>
        </div>
    </div>
</body>
</html>
