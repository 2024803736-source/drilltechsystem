<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: loginAD.php");
    exit();
}
include("database.php");

// Total Projects
$totalProjects = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM project"))['total'];

// Total Revenue
$totalRevenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(Project_Value) AS revenue FROM project"))['revenue'];

// Payroll Summary
$payrollSummary = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(Payroll_Amount) AS total FROM payroll"))['total'];

// Active Clients
$activeClients = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT Client_ID) AS clients FROM project WHERE Project_Status='On Going'"))['clients'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<style>
body {margin:0; font-family:Arial,sans-serif; background:#f4f4f4;}
.header {background:#e0e0e0; padding:15px; font-size:20px; color:#000; border-bottom:1px solid #ccc;}
.sidebar {width:220px; background:#d6d6d6; height:100vh; position:fixed; top:0; left:0; padding-top:30px; border-right:1px solid #bbb;}
.sidebar a {display:block; color:#000; padding:12px; text-decoration:none;}
.sidebar a:hover {background:#c0c0c0;}
.content {margin-left:220px; padding:20px;}
.cards {display:grid; grid-template-columns: repeat(2, 1fr); gap:20px;}
.card {background:#f9f9f9; border:1px solid #ccc; padding:20px; border-radius:10px; text-align:center;}
.card h3 {margin:0 0 10px; color:#333;}
</style>
</head>
<body>
    <!-- Header -->
    <div class="header">Welcome, Admin</div>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="admin.php" style="background:#c0c0c0;">Dashboard</a>
        <a href="projectAD.php">Project</a>
        <a href="employeeAD.php">Employee</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>Dashboard Overview</h2>
        <div class="cards">
            <div class="card"><h3>Total Projects</h3><p><?php echo $totalProjects; ?></p></div>
            <div class="card"><h3>Total Revenue</h3><p>RM<?php echo number_format($totalRevenue,2); ?></p></div>
            <div class="card"><h3>Payroll Summary</h3><p>RM<?php echo number_format($payrollSummary,2); ?></p></div>
            <div class="card"><h3>Active Clients</h3><p><?php echo $activeClients; ?></p></div>
        </div>
    </div>
</body>
</html>
