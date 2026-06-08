<?php
session_start();
include("database.php");

if(!isset($_SESSION['client_id'])){
    header("Location: loginCL.php");
    exit();
}

$client_id = $_SESSION['client_id'];
$client_name = $_SESSION['client_name'];

// Statistics
$pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM project WHERE Client_ID=$client_id AND Project_Status='Pending'"))['c'] ?? 0;
$active  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM project WHERE Client_ID=$client_id AND Project_Status='On Going'"))['c'] ?? 0;
$completed = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM project WHERE Client_ID=$client_id AND Project_Status='Completed'"))['c'] ?? 0;

// Recent Updates (Last 4 projects)
$recent = mysqli_query($conn, "SELECT Project_Name, Project_Status FROM project WHERE Client_ID=$client_id ORDER BY Project_ID DESC LIMIT 4");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DrillTech HDD</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.65)), url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: white;
            min-height: 100vh;
        }
        .header {
            background: #003087;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo {
            display: flex;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
        }
        .logo img { height: 45px; margin-right: 10px; }
        .sidebar {
            width: 240px;
            background: #003087;
            position: fixed;
            height: 100vh;
            padding-top: 20px;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #ff8c00;
        }
        .content {
            margin-left: 260px;
            padding: 30px;
        }
        .welcome { font-size: 28px; margin-bottom: 25px; }
        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            flex: 1;
            background: rgba(0,0,0,0.7);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .stat-number {
            font-size: 42px;
            font-weight: bold;
        }
        .recent-updates {
            background: rgba(0,0,0,0.7);
            padding: 25px;
            border-radius: 10px;
        }
        .recent-updates h3 { margin-bottom: 15px; border-bottom: 2px solid #ff8c00; padding-bottom: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <span style="font-size:32px;">🔧</span> 
            DRILLTECH
        </div>
        <div>Welcome, <?php echo htmlspecialchars($client_name); ?></div>
    </div>

    <div class="sidebar">
        <a href="client.php" class="active">📊 DASHBOARD</a>
        <a href="projectCL.php">🔍 PROJECT</a>
        <a href="paymentCL.php">💰 PAYMENT</a>
    </div>

    <div class="content">
        <h1 class="welcome">Welcome, <?php echo htmlspecialchars($client_name); ?></h1>

        <div class="stats">
            <div class="stat-card">
                <div>PENDING REQUESTS</div>
                <div class="stat-number" style="color:#ffcc00;"><?php echo $pending; ?></div>
                <small>Awaiting Approval</small>
            </div>
            <div class="stat-card">
                <div>ACTIVE PROJECTS</div>
                <div class="stat-number" style="color:#00cc66;"><?php echo $active; ?></div>
                <small>In Progress</small>
            </div>
            <div class="stat-card">
                <div>COMPLETED PROJECTS</div>
                <div class="stat-number" style="color:#3399ff;"><?php echo $completed; ?></div>
                <small>Completed</small>
            </div>
        </div>

        <div class="recent-updates">
            <h3>Recent Updates:</h3>
            <ul style="list-style: none;">
                <?php while($row = mysqli_fetch_assoc($recent)): ?>
                    <li style="margin: 12px 0;">
                        ► <strong><?php echo htmlspecialchars($row['Project_Name']); ?></strong> 
                        - <?php echo $row['Project_Status']; ?>
                    </li>
                <?php endwhile; ?>
                <?php if(mysqli_num_rows($recent) == 0): ?>
                    <li>No recent updates available.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</body>
</html>