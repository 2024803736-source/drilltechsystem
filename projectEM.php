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
    <title>Projects - DrillTech HDD</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.65)), url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: white;
            min-height: 100vh;
        }
        .header {
            background: #004d00;
            padding: 15px 30px;
            display:flex; align-items:center; justify-content:space-between;
        }
        .logo { font-size:26px; font-weight:bold; }
        .sidebar {
            width:240px; background:#004d00; position:fixed; height:100vh; padding-top:20px;
        }
        .sidebar a {
            display:flex; align-items:center; padding:15px 25px; color:white; text-decoration:none; gap:10px;
        }
        .sidebar a:hover, .sidebar a.active { background:#ff8c00; }
        .content { margin-left:260px; padding:30px; }
        .main-box {
            background:rgba(0,0,0,0.8); border-radius:12px; padding:25px;
        }
        table { width:100%; border-collapse:collapse; margin-top:15px; }
        th, td { padding:14px 12px; text-align:left; border-bottom:1px solid rgba(255,255,255,0.2); }
        th { background:#004d00; color:#ffcc00; }
        .status {
            padding:6px 18px; border-radius:20px; font-size:14px; font-weight:bold; display:inline-block;
        }
        .status-ongoing { background:#28a745; color:white; }
        .status-completed { background:#007bff; color:white; }
        .status-pending { background:#ffc107; color:black; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">👷 DRILLTECH</div>
        <div>Welcome, <?php echo htmlspecialchars($employeeName); ?></div>
    </div>

    <div class="sidebar">
        <a href="employee.php">📊 DASHBOARD</a>
        <a href="projectEM.php" class="active">🔍 PROJECT</a>
        <a href="profileEM.php">👤 PROFILE</a>
        <a href="payrollEM.php">💰 PAYROLL</a>
    </div>

    <div class="content">
        <div class="main-box">
            <h2>Your Projects:</h2>
            <table>
                <tr>
                    <th>Project ID</th>
                    <th>Project Name</th>
                    <th>Status</th>
                    <th>Value (RM)</th>
                    <th>Due Date</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($projects)): ?>
                <tr>
                    <td><?php echo $row['Project_ID']; ?></td>
                    <td><?php echo htmlspecialchars($row['Project_Name']); ?></td>
                    <td>
                        <?php 
                        $status = $row['Project_Status'];
                        $class = ($status == 'On Going') ? 'status-ongoing' : 
                                (($status == 'Completed') ? 'status-completed' : 'status-pending');
                        echo "<span class='status $class'>$status</span>";
                        ?>
                    </td>
                    <td>RM <?php echo number_format($row['Project_Value'],2); ?></td>
                    <td><?php echo !empty($row['ProjectEmp_EndD']) ? $row['ProjectEmp_EndD'] : 'N/A'; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>
