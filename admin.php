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
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(rgba(74, 74, 74, 0.45), rgba(15, 15, 15, 0.95)), 
                        url('backgroundCSC264.png') center/cover no-repeat fixed;
            color: #f8fafc;
            min-height: 100vh;
        }

        .header {
            background: #4a4a4a;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 60px;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        
        .logo {
            display: flex;
            align-items: center;
            font-size: 20px;
            font-weight: 800;
            color: white;
            letter-spacing: 1px;
        }
        
        .header-welcome {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 6px 14px;
            border-radius: 20px;
        }

        /* Logout Button */
        .logout-btn {
            color: #ff6b6b;
            font-weight: 600;
            text-decoration: none;
            padding: 6px 14px;
            border: 1px solid rgba(255, 107, 107, 0.3);
            border-radius: 6px;
            transition: all 0.2s;
        }
        .logout-btn:hover {
            background: rgba(255, 107, 107, 0.1);
            color: #ff5252;
        }

        .wrapper {
            display: flex;
            margin-top: 60px;
        }

        .sidebar {
            width: 240px;
            background: #5a5a5a;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            min-height: calc(100vh - 60px);
            position: fixed;
            top: 60px;
            left: 0;
            z-index: 90;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.15);
        }
        
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 14px 25px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.2s ease;
        }
        
        .sidebar a:hover, .sidebar a.active {
            color: #fff;
            background: #7a7a7a;
        }

        .content {
            flex: 1;
            margin-left: 240px;
            padding: 35px 30px;
        }
        
        .welcome {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 25px;
            letter-spacing: 0.5px;
            color: #fff;
        }

        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .stat-card {
            flex: 1;
            min-width: 200px;
            background: rgba(45, 45, 45, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 24px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5);
        }

        .stat-card div:first-child {
            font-size: 12px;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .stat-number {
            font-size: 38px;
            font-weight: 800;
            margin: 6px 0;
        }
        
        .stat-card small {
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
        }

        .recent-updates {
            background: rgba(45, 45, 45, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 28px;
            border-radius: 12px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
        }
        
        .recent-updates h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            border-bottom: 2px solid #888;
            padding-bottom: 10px;
            letter-spacing: 0.5px;
            color: #fff;
        }

        .recent-updates ul {
            list-style: none;
        }

        .recent-updates li {
            padding: 14px 16px;
            margin-bottom: 8px;
            background: rgba(0, 0, 0, 0.2);
            border-left: 4px solid #ff8c00;
            border-radius: 0 6px 6px 0;
            font-size: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .project-name {
            font-weight: 600;
            color: #fff;
        }

        .project-status {
            font-size: 12px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            text-transform: uppercase;
        }

        .status-pending { background: rgba(255, 204, 0, 0.12); color: #ffcc00; border: 1px solid rgba(255, 204, 0, 0.25); }
        .status-ongoing { background: rgba(0, 204, 102, 0.12); color: #00cc66; border: 1px solid rgba(0, 204, 102, 0.25); }
        .status-completed { background: rgba(51, 153, 255, 0.12); color: #3399ff; border: 1px solid rgba(51, 153, 255, 0.25); }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo">
            <img src="images/logo.png" alt="Logo" style="height: 65px; width: auto; display: block; object-fit: contain; filter: drop-shadow(0px 0px 8px rgba(255, 255, 255, 0.65));">
        </div>
        <div style="display:flex; align-items:center; gap:15px;">
            <div class="header-welcome">Welcome, <?php echo htmlspecialchars($admin_name); ?></div>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="wrapper">

        <div class="sidebar">
            <a href="admin.php" class="active">📊 DASHBOARD</a>
            <a href="projectAD.php">📁 PROJECT</a>
            <a href="employeeAD.php">👷 EMPLOYEE</a>
        </div>

        <div class="content">
            <h1 class="welcome">Welcome, <?php echo htmlspecialchars($admin_name); ?></h1>

            <div class="stats">
                <div class="stat-card">
                    <div>TOTAL PROJECTS</div>
                    <div class="stat-number" style="color: #e2e8f0;"><?php echo $totalProjects; ?></div>
                    <small>All Projects</small>
                </div>
                <div class="stat-card">
                    <div>TOTAL REVENUE</div>
                    <div class="stat-number" style="color: #34d399;">RM<?php echo number_format($totalRevenue, 2); ?></div>
                    <small>Overall Income</small>
                </div>
                <div class="stat-card">
                    <div>PAYROLL SUMMARY</div>
                    <div class="stat-number" style="color: #f87171;">RM<?php echo number_format($payrollSummary, 2); ?></div>
                    <small>Total Payroll</small>
                </div>
                <div class="stat-card">
                    <div>ACTIVE PROJECTS</div>
                    <div class="stat-number" style="color: #60a5fa;"><?php echo $activeClients; ?></div>
                    <small>On Going Projects</small>
                </div>
            </div>

            <div class="recent-updates">
                <h3>Recent Updates:</h3>
                <ul>
                    <?php
                    $recent = mysqli_query($conn, "SELECT Project_Name, Project_Status FROM project ORDER BY Project_ID DESC LIMIT 4");
                    while($row = mysqli_fetch_assoc($recent)):
                        $status = strtolower(trim($row['Project_Status']));
                        $statusClass = 'status-pending';
                        
                        if ($status === 'on going' || $status === 'ongoing' || $status === 'active') {
                            $statusClass = 'status-ongoing';
                        } elseif ($status === 'completed' || $status === 'done') {
                            $statusClass = 'status-completed';
                        }
                    ?>
                        <li>
                            <span class="project-name">► <?php echo htmlspecialchars($row['Project_Name']); ?></span>
                            <span class="project-status <?php echo $statusClass; ?>"><?php echo htmlspecialchars($row['Project_Status']); ?></span>
                        </li>
                    <?php endwhile; ?>
                    
                    <?php if(mysqli_num_rows($recent) == 0): ?>
                        <li style="border-left: none; color: #64748b; font-style: italic; text-align: center; display: block; padding: 16px;">
                            No updates available.
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

    </div>

</body>
</html>