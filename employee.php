<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: loginEM.php");
    exit();
}
include("database.php");

// Cari Employee_ID berdasarkan username login
$employeeName = $_SESSION['username'];
$empQuery = mysqli_query($conn, "SELECT Employee_ID FROM employee WHERE Employee_Name='$employeeName'");
$empRow = mysqli_fetch_assoc($empQuery);
$employeeID = $empRow['Employee_ID'];

// Statistik projek untuk employee login
$pending = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as c 
    FROM project p 
    JOIN assigned_employee ae ON p.Project_ID = ae.Project_ID
    WHERE ae.Employee_ID='$employeeID' AND p.Project_Status='Pending'
"))['c'] ?? 0;

$active = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as c 
    FROM project p 
    JOIN assigned_employee ae ON p.Project_ID = ae.Project_ID
    WHERE ae.Employee_ID='$employeeID' AND p.Project_Status='On Going'
"))['c'] ?? 0;

$completed = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as c 
    FROM project p 
    JOIN assigned_employee ae ON p.Project_ID = ae.Project_ID
    WHERE ae.Employee_ID='$employeeID' AND p.Project_Status='Completed'
"))['c'] ?? 0;

// Recent Updates projek yang employee login terlibat
$recentUpdates = mysqli_query($conn, "
    SELECT p.Project_Name, p.Project_Status 
    FROM project p
    JOIN assigned_employee ae ON p.Project_ID = ae.Project_ID
    WHERE ae.Employee_ID='$employeeID'
    ORDER BY p.Project_ID DESC LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - DrillTech HDD</title>
    <style>
        /* CSS Reset & Modern Typography */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            /* Lighter green overlay using your corporate #004d00 color */
            background: linear-gradient(rgba(0, 77, 0, 0.45), rgba(15, 23, 18, 0.85)), 
                        url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: #f8fafc;
            min-height: 100vh;
        }

        /* Top Header Navigation (#004d00 green) */
        .header {
            background: #004d00;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            padding: 0 30px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        
        .logo { 
            font-size: 20px; 
            font-weight: 800; 
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-welcome {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 6px 14px;
            border-radius: 20px;
        }

        /* Side Navigation Panel (#004d00 green) */
        .sidebar {
            width: 240px;
            background: #004d00;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            position: fixed;
            top: 64px;
            left: 0;
            height: calc(100vh - 64px);
            padding-top: 20px;
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
        
        /* Sidebar Hover and Active (#ff8c00 orange) */
        .sidebar a:hover { 
            color: #fff;
            background: #ff8c00;
        }

        .sidebar a.active { 
            color: #fff;
            background: #ff8c00; 
        }

        /* Main Dashboard Content Layout */
        .content { 
            margin-left: 260px; 
            padding: 94px 30px 40px 30px; 
        }

        .welcome { 
            font-size: 26px; 
            font-weight: 700;
            margin-bottom: 25px; 
            letter-spacing: 0.5px;
        }

        /* Stats Cards Flex Grid */
        .stats { 
            display: flex; 
            gap: 20px; 
            margin-bottom: 30px; 
            flex-wrap: wrap;
        }

        .stat-card {
            flex: 1; 
            min-width: 200px;
            /* Semi-transparent dark cards with subtle green borders */
            background: rgba(0, 0, 0, 0.65);
            border: 1px solid rgba(0, 77, 0, 0.35);
            padding: 24px; 
            border-radius: 12px; 
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0, 77, 0, 0.25);
            border-color: rgba(40, 167, 69, 0.5);
        }

        .stat-label {
            font-size: 12px;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .stat-number { 
            font-size: 46px; 
            font-weight: 800; 
            margin-bottom: 6px;
        }

        .stat-card small {
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
        }

        /* Recent Activity Log Card */
        .recent-updates {
            background: rgba(0, 0, 0, 0.65);
            border: 1px solid rgba(0, 77, 0, 0.3);
            padding: 28px; 
            border-radius: 12px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
        }

        .recent-updates h3 { 
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px; 
            border-bottom: 2px solid #ff8c00; 
            padding-bottom: 10px; 
            letter-spacing: 0.5px;
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

        .recent-updates li:last-child {
            margin-bottom: 0;
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

        /* Status Colors */
        .status-pending { background: rgba(255, 204, 0, 0.12); color: #ffcc00; border: 1px solid rgba(255, 204, 0, 0.25); }
        .status-ongoing { background: rgba(0, 204, 102, 0.12); color: #00cc66; border: 1px solid rgba(0, 204, 102, 0.25); }
        .status-completed { background: rgba(51, 153, 255, 0.12); color: #3399ff; border: 1px solid rgba(51, 153, 255, 0.25); }

        .no-data {
            padding: 16px;
            color: #64748b;
            text-align: center;
            font-style: italic;
            border-left: none !important;
        }
    </style>
</head>
<body>
    <!-- Top Header Navigation -->
    <div class="header">
        <div class="logo">
            <img src="images/logo.png" alt="Logo" style="height: 65px; width: auto; display: block; object-fit: contain; filter: drop-shadow(0px 0px 8px rgba(255, 255, 255, 0.65));">
        </div>
        <div class="user-welcome">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></div>
    </div>

    <!-- Side Navigation Bar -->
    <div class="sidebar">
        <a href="employee.php" class="active">📊 DASHBOARD</a>
        <a href="projectEM.php">🔍 PROJECT</a>
        <a href="profileEM.php">👤 PROFILE</a>
        <a href="payrollEM.php">💰 PAYROLL</a>
    </div>

    <!-- Main View Panel -->
    <div class="content">
        <h1 class="welcome">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>

        <!-- Stats Container -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-label">PENDING PROJECTS</div>
                <div class="stat-number" style="color:#ffcc00;"><?php echo $pending; ?></div>
                <small>Awaiting Approval</small>
            </div>
            <div class="stat-card">
                <div class="stat-label">ACTIVE PROJECTS</div>
                <div class="stat-number" style="color:#00cc66;"><?php echo $active; ?></div>
                <small>In Progress</small>
            </div>
            <div class="stat-card">
                <div class="stat-label">COMPLETED PROJECTS</div>
                <div class="stat-number" style="color:#3399ff;"><?php echo $completed; ?></div>
                <small>Completed</small>
            </div>
        </div>

        <!-- Recent Project Updates List -->
        <div class="recent-updates">
            <h3>Recent Updates:</h3>
            <ul>
                <?php while($row = mysqli_fetch_assoc($recentUpdates)): 
                    // Dynamic styling based on the project status
                    $status = strtolower(trim($row['Project_Status']));
                    $statusClass = 'status-pending'; // default
                    
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
                
                <?php if(mysqli_num_rows($recentUpdates) == 0): ?>
                    <li class="no-data">No recent updates available.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</body>
</html>