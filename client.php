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
    <title>Client Dashboard</title>
    <style>
        body {background:#ffffff; font-family:Arial; color:#000000; margin:0;}
        .header {background:#004080; padding:15px; font-size:20px; color:#ffffff;}
        .sidebar {width:200px; background:#004080; height:100vh; position:fixed; padding-top:30px;}
        .sidebar a {display:block; color:#ffffff; padding:12px; text-decoration:none;}
        .sidebar a:hover {background:#1E90FF;}
        .content {margin-left:220px; padding:20px; background:#ffffff; min-height:100vh;}
        .card {background:#ffffff; border:1px solid #ccc; padding:20px; border-radius:10px; margin-bottom:20px;}
        h2 {color:#004080;}
        .status-boxes {display:flex; gap:20px; margin-bottom:20px;}
        .status {flex:1; background:#e6f0ff; border:1px solid #004080; padding:20px; border-radius:10px; text-align:center;}
        .status h3 {color:#004080; margin:0 0 10px;}
        .updates ul {list-style-type:none; padding:0;}
        .updates li {margin-bottom:8px;}
    </style>
</head>
<body>
    <div class="header">Welcome, <?php echo $_SESSION['client_name']; ?></div>

<div class="sidebar">
    <a href="client.php">Dashboard</a>
    <a href="projectCL.php">Project</a>
    <a href="paymentCL.php">Payment</a>
</div>

    <div class="content">
        <div class="card">
            <h2>Dashboard Overview</h2>
            <div class="status-boxes">
                <div class="status">
                    <h3>Pending Requests</h3>
                    <p>NA</p>
                </div>
                <div class="status">
                    <h3>Active Projects</h3>
                    <p>NA</p>
                </div>
                <div class="status">
                    <h3>Completed Projects</h3>
                    <p>NA</p>
                </div>
            </div>
        </div>

        <div class="card updates">
            <h2>Recent Updates</h2>
            
                XX<br><br>
                XX<br><br>
                XX<br><br>
                XX<br><br>

        </div>
    </div>
</body>
</html>
