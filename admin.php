<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: loginAD.php");
    exit();
}
if(!isset($_SESSION['admin_name'])){
    $_SESSION['admin_name'] = "Demo Admin"; // fallback kalau login tak set nama
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {margin:0; font-family:Arial; background:#f9f9f9;}
        .header {background:#444444; padding:15px; color:#fff; font-size:20px;}
        .sidebar {
            width:200px; background:#333333; height:100vh;
            position:fixed; top:0; left:0; padding-top:30px;
        }
        .sidebar a {
            display:block; color:#fff; padding:12px; text-decoration:none;
        }
        .sidebar a:hover {background:#555555;}
        .content {margin-left:220px; padding:20px;}
        .cards {display:flex; gap:20px; margin-bottom:20px;}
        .card {
            flex:1; background:#fff; border:1px solid #ccc;
            padding:20px; border-radius:10px; text-align:center;
        }
        .card h3 {margin:0 0 10px; color:#444444;}
        table {width:100%; border-collapse:collapse; background:#fff;}
        th, td {border:1px solid #ccc; padding:10px; text-align:center;}
        th {background:#444444; color:#fff;}
        .status-ongoing {color:blue;}
        .status-completed {color:green;}
        .status-pending button {margin:0 5px;}
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">Welcome, <?php echo $_SESSION['admin_name']; ?></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="admin.php">Dashboard</a>
        <a href="projectEM.php">Project</a>
        <a href="employee.php">Employee</a>
        <a href="payrollEM.php">Payroll</a>
        <a href="report.php">Report</a>
    </div>

    <!-- Content -->
    <div class="content">
        <h2>Dashboard Overview</h2>
        <div class="cards">
            <div class="card"><h3>Total Projects</h3><p>12</p></div>
            <div class="card"><h3>Monthly Revenue</h3><p>RM85,000</p></div>
            <div class="card"><h3>Payroll Summary</h3><p>RM45,000</p></div>
            <div class="card"><h3>Active Clients</h3><p>8</p></div>
        </div>

        <h2>Project List</h2>
        <table>
            <tr><th>Name Projects</th><th>Date</th><th>Status</th></tr>
            <tr><td>Site Alpha</td><td>08/05/2026</td><td><span class="status-ongoing">On Going</span></td></tr>
            <tr><td>Pipeline Delta</td><td>04/25/2026</td><td class="status-pending"><button>Accept</button><button>Reject</button></td></tr>
            <tr><td>River Crossing</td><td>02/15/2026</td><td><span class="status-completed">Completed</span></td></tr>
        </table>
    </div>
</body>
</html>
