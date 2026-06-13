<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: loginEM.php");
    exit();
}
include("database.php");

// Ambil Employee_ID dari session
$employeeID = $_SESSION['employee_id'];
$employeeName = $_SESSION['username'];

// Query projek yang employee tu commit
$projects = mysqli_query($conn, "
    SELECT p.Project_ID, p.Project_Name, p.Project_Status, ae.ProjectEmp_EndD, p.Project_Value
    FROM project p
    JOIN assigned_employee ae ON p.Project_ID = ae.Project_ID
    WHERE ae.Employee_ID = '$employeeID'
    ORDER BY p.Project_ID DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - DrillTech HDD</title>
    <style>
        /* CSS Reset & Modern Typography */
        * { margin:0; padding:0; box-sizing:border-box; }
        
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            /* Lighter green overlay matching the dashboard */
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
            height: 100vh; 
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
            gap: 10px;
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

        /* Table Card Container */
        .main-box {
            background: rgba(0, 0, 0, 0.65); 
            border: 1px solid rgba(0, 77, 0, 0.3);
            border-radius: 12px; 
            padding: 28px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
        }

        .main-box h2 {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
            border-bottom: 2px solid #ff8c00;
            padding-bottom: 10px;
        }

        /* Styled Table */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px; 
        }
        
        th, td { 
            padding: 16px 14px; 
            text-align: left; 
            border-bottom: 1px solid rgba(255, 255, 255, 0.08); 
            font-size: 14px;
        }

        tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }
        
        th { 
            background: rgba(0, 77, 0, 0.4); 
            color: #ffcc00; 
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Status Badges */
        .status {
            padding: 5px 12px; 
            border-radius: 20px; 
            font-size: 12px; 
            font-weight: 700; 
            display: inline-block;
            text-transform: uppercase;
        }
        
        .status-ongoing { 
            background: rgba(0, 204, 102, 0.12); 
            color: #00cc66; 
            border: 1px solid rgba(0, 204, 102, 0.25); 
        }
        
        .status-completed { 
            background: rgba(51, 153, 255, 0.12); 
            color: #3399ff; 
            border: 1px solid rgba(51, 153, 255, 0.25); 
        }
        
        .status-pending { 
            background: rgba(255, 204, 0, 0.12); 
            color: #ffcc00; 
            border: 1px solid rgba(255, 204, 0, 0.25); 
        }
    </style>
</head>
<body>
   
<div class="header">
        <div class="logo">
            <img src="images/logo.png" alt="Logo" style="height: 65px; width: auto; display: block; object-fit: contain; filter: drop-shadow(0px 0px 8px rgba(255, 255, 255, 0.65));">
        </div>
        <div class="user-welcome">Welcome, <?php echo htmlspecialchars($employeeName); ?></div>
    </div>
    <!-- Side Navigation Bar -->
    <div class="sidebar">
        <a href="employee.php">📊 DASHBOARD</a>
        <a href="projectEM.php" class="active">🔍 PROJECT</a>
        <a href="profileEM.php">👤 PROFILE</a>
        <a href="payrollEM.php">💰 PAYROLL</a>
    </div>

    <!-- Main View Panel -->
    <div class="content">
        <div class="main-box">
            <h2>Your Projects:</h2>
            <table>
                <thead>
                    <tr>
                        <th>Project ID</th>
                        <th>Project Name</th>
                        <th>Status</th>
                        <th>Value (RM)</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($projects)): ?>
                    <tr>
                        <td><strong>#<?php echo $row['Project_ID']; ?></strong></td>
                        <td><?php echo htmlspecialchars($row['Project_Name']); ?></td>
                        <td>
                            <?php 
                            $status = $row['Project_Status'];
                            $class = ($status == 'On Going' || $status == 'Ongoing') ? 'status-ongoing' : 
                                    (($status == 'Completed') ? 'status-completed' : 'status-pending');
                            echo "<span class='status $class'>$status</span>";
                            ?>
                        </td>
                        <td>RM <?php echo number_format($row['Project_Value'], 2); ?></td>
                        <td><?php echo !empty($row['ProjectEmp_EndD']) ? htmlspecialchars($row['ProjectEmp_EndD']) : 'N/A'; ?></td>
                    </tr>
                    <?php endwhile; ?>
                    
                    <?php if(mysqli_num_rows($projects) == 0): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #64748b; font-style: italic; border: none;">
                                No projects assigned to you.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>