<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: loginEM.php");
    exit();
}
include("database.php");

$employeeID = $_SESSION['employee_id'];

// Ambil payroll untuk employee login
$result = mysqli_query($conn, "SELECT * FROM payroll WHERE Employee_ID='$employeeID'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payroll - DrillTech HDD</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.65)), url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: white;
            min-height: 100vh;
        }
        .header {
            background:#004d00; /* hijau gelap */
            padding:15px 30px;
            display:flex; align-items:center; justify-content:space-between;
        }
        .logo { font-size:26px; font-weight:bold; }
        .sidebar {
            width:240px; background:#004d00; position:fixed; height:100vh; padding-top:20px;
        }
        .sidebar a {
            display:flex; align-items:center; padding:15px 25px; color:white; text-decoration:none; gap:10px;
        }
        .sidebar a:hover, .sidebar a.active { background:#228B22; } /* hijau hover */
        .content { margin-left:260px; padding:30px; }
        .main-box {
            background:rgba(0,0,0,0.8); border-radius:12px; padding:25px;
        }
        table { width:100%; border-collapse:collapse; margin-top:15px; }
        th, td { padding:14px 12px; text-align:left; border-bottom:1px solid rgba(255,255,255,0.2); }
        th { background:#004d00; color:#FFD700; } /* hijau + emas */
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">👷 DRILLTECH</div>
        <div>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></div>
    </div>

    <div class="sidebar">
        <a href="employee.php">📊 DASHBOARD</a>
        <a href="projectEM.php">🔍 PROJECT</a>
        <a href="profileEM.php">👤 PROFILE</a>
        <a href="payrollEM.php" class="active">💰 PAYROLL</a>
    </div>

    <div class="content">
        <div class="main-box">
            <h2>Payroll Management Dashboard</h2>
            <table>
                <tr>
                    <th>Payroll ID</th>
                    <th>Employee ID</th>
                    <th>Payroll Date</th>
                    <th>Payroll Amount</th>
                    <th>Payroll Status</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['Payroll_ID']; ?></td>
                    <td><?php echo $row['Employee_ID']; ?></td>
                    <td><?php echo $row['Payroll_Date']; ?></td>
                    <td>RM <?php echo number_format($row['Payroll_Amount'],2); ?></td>
                    <td><?php echo $row['Payroll_Status']; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>
