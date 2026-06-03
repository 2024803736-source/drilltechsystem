<?php
session_start();
if(!isset($_SESSION['client_id'])){
    header("Location: loginCL.php");
    exit();
}
if(!isset($_SESSION['client_name'])){
    $_SESSION['client_name'] = "Demo Client";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Client Projects</title>
    <style>
        body {
            background:#ffffff; 
            font-family:Arial; 
            color:#000000; 
            margin:0;
        }
        .header {
            background:#004080; 
            padding:15px; font-size:20px; color:#ffffff;}
        .sidebar {width:200px; background:#004080; height:100vh; position:fixed; padding-top:30px;}
        .sidebar a {display:block; color:#ffffff; padding:12px; text-decoration:none;}
        .sidebar a:hover {background:#1E90FF;}
        .content {margin-left:220px; padding:20px; background:#ffffff; min-height:100vh;}
        .card {background:#ffffff; border:1px solid #ccc; padding:20px; border-radius:10px; margin-bottom:20px;}
        h2 {color:#004080;}
        table {width:100%; border-collapse:collapse; margin-top:15px;}
        table, th, td {border:1px solid #004080;}
        th, td {padding:10px; text-align:center;}
        th {background:#e6f0ff; color:#004080;}
        .buttons {margin-top:20px;}
        .btn {padding:10px 20px; border:none; border-radius:5px; cursor:pointer; font-size:14px;}
        .btn-blue {background:#1E90FF; color:#fff;}
    </style>
</head>
<body>
    <div class="header">Projects - <?php echo $_SESSION['client_name']; ?></div>

<div class="sidebar">
    <a href="client.php">Dashboard</a>
    <a href="projectCL.php">Project</a>
    <a href="paymentCL.php">Payment</a>
</div>

    <div class="content">
        <div class="card">
            <h2>Projects</h2>
            <table>
                <tr>
                    <th>Project ID</th><th>Project Name</th><th>Status</th><th>Value</th><th>Deadline</th>
                </tr>
                <tr><td>A01</td><td>Site Alpha</td><td>On Going</td><td>RM80,000</td><td>15/06/2026</td></tr>
                <tr><td>A02</td><td>Pipeline Delta</td><td>On Going</td><td>RM80,000</td><td>01/07/2026</td></tr>
                <tr><td>A03</td><td>River Crossing Beta</td><td>Completed</td><td>RM80,000</td><td>20/08/2026</td></tr>
            </table>
            <div class="buttons">
                <button class="btn btn-blue">Add Project (+)</button>
            </div>
        </div>
    </div>
</body>
</html>
