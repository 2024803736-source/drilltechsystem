<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
if(!isset($_SESSION['admin_id'])){
    header("Location: loginAD.php");
    exit();
}
include("database.php");

$admin_name = $_SESSION['admin_name'] ?? 'Admin';

$totalProjects = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM project"))['total'] ?? 0;
$totalRevenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(Project_Value) AS revenue FROM project"))['revenue'] ?? 0;
$payrollSummary = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(Payroll_Amount) AS total FROM payroll"))['total'] ?? 0;
$activeClients = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS clients FROM project WHERE Project_Status='On Going'"))['clients'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DrillTech Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
         body {
            font-family: Arial, sans-serif;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.65)), 
                        url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: black;
            min-height: 100vh;
            margin: 0;
        }

        /* ===== HEADER ===== */
        .header {
            background: #4a4a4a;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 60px;
            z-index: 1000;
        }
        .logo {
            display: flex;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
            color: white;
        }
        .header-welcome {
            color: white;
            font-size: 15px;
        }

        /* ===== LAYOUT ===== */
        .wrapper {
            display: flex;
            margin-top: 60px; /* tinggi header */
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 240px;
            background: #5a5a5a;
            min-height: calc(100vh - 60px);
            flex-shrink: 0;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #7a7a7a;
        }

        /* ===== CONTENT ===== */
        .content {
            flex: 1;
            padding: 30px;
        }
        .welcome {
            font-size: 28px;
            margin-bottom: 25px;
            color: #fdf7f7;
        }

        /* ===== STAT CARDS ===== */
        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            flex: 1;
            background: #dcdcdc;
            border: 1px solid #bbb;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .stat-number {
            font-size: 42px;
            font-weight: bold;
            margin: 8px 0;
        }

        /* ===== RECENT UPDATES ===== */
        .recent-updates {
            background: #dcdcdc;
            border: 1px solid #bbb;
            padding: 25px;
            border-radius: 10px;
        }
        .recent-updates h3 {
            margin-bottom: 15px;
            border-bottom: 2px solid #888;
            padding-bottom: 8px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="logo">
            <span style="font-size:32px; margin-right:10px;">🔧</span>
            DRILLTECH
        </div>
        <div class="header-welcome">Welcome, <?php echo htmlspecialchars($admin_name); ?></div>
    </div>

    <!-- Wrapper -->
    <div class="wrapper">

        <!-- Sidebar -->
        <div class="sidebar">
            <a href="admin.php" class="active">📊 DASHBOARD</a>
            <a href="projectAD.php">📁 PROJECT</a>
            <a href="employeeAD.php">👷 EMPLOYEE</a>
        </div>

        <!-- Content -->
        <div class="content">
            <h1 class="welcome">Welcome, <?php echo htmlspecialchars($admin_name); ?></h1>

            <div class="stats">
                <div class="stat-card">
                    <div>TOTAL PROJECTS</div>
                    <div class="stat-number" style="color:#555;"><?php echo $totalProjects; ?></div>
                    <small>All Projects</small>
                </div>
                <div class="stat-card">
                    <div>TOTAL REVENUE</div>
                    <div class="stat-number" style="color:#2a7a2a;">RM<?php echo number_format($totalRevenue, 2); ?></div>
                    <small>Overall Income</small>
                </div>
                <div class="stat-card">
                    <div>PAYROLL SUMMARY</div>
                    <div class="stat-number" style="color:#7a2a2a;">RM<?php echo number_format($payrollSummary, 2); ?></div>
                    <small>Total Payroll</small>
                </div>
                <div class="stat-card">
                    <div>ACTIVE PROJECTS</div>
                    <div class="stat-number" style="color:#2a2a7a;"><?php echo $activeClients; ?></div>
                    <small>On Going Projects</small>
                </div>
            </div>

            <div class="recent-updates">
                <h3>Recent Updates:</h3>
                <ul style="list-style: none;">
                    <?php
                    $recent = mysqli_query($conn, "SELECT Project_Name, Project_Status FROM project ORDER BY Project_ID DESC LIMIT 4");
                    while($row = mysqli_fetch_assoc($recent)):
                    ?>
                        <li style="margin: 12px 0;">
                            ► <strong><?php echo htmlspecialchars($row['Project_Name']); ?></strong>
                            - <?php echo $row['Project_Status']; ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>

    </div><!-- end wrapper -->

</body>
</html>
