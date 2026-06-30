<?php
session_start();
include("database.php");

if(!isset($_SESSION['username'])){
    header("Location: loginEM.php");
    exit();
}

$employeeID = $_SESSION['employee_id'];

// Ambil payroll untuk employee login
$result = mysqli_query($conn, "SELECT * FROM payroll WHERE Employee_ID='$employeeID'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll - DrillTech HDD</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(rgba(0, 77, 0, 0.45), rgba(15, 23, 18, 0.85)), 
                        url('backgroundCSC264.png') center/cover no-repeat fixed;
            color: #f8fafc;
            min-height: 100vh;
        }

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
            gap: 10px;
        }
        
        .sidebar a:hover { 
            color: #fff;
            background: #ff8c00; 
        }
        
        .sidebar a.active { 
            color: #fff;
            background: #ff8c00; 
        }

        .content { 
            margin-left: 260px; 
            padding: 94px 30px 40px 30px; 
        }

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

        .status {
            padding: 5px 12px; 
            border-radius: 20px; 
            font-size: 12px; 
            font-weight: 700; 
            display: inline-block;
            text-transform: uppercase;
        }
        
        .status-paid { 
            background: rgba(0, 204, 102, 0.12); 
            color: #00cc66; 
            border: 1px solid rgba(0, 204, 102, 0.25); 
        }
        
        .status-unpaid { 
            background: rgba(239, 68, 68, 0.12); 
            color: #f87171; 
            border: 1px solid rgba(239, 68, 68, 0.25); 
        }

        .status-pending { 
            background: rgba(255, 204, 0, 0.12); 
            color: #ffcc00; 
            border: 1px solid rgba(255, 204, 0, 0.25); 
        }

        .btn-slip {
            background: #ff8c00;
            color: white;
            padding: 6px 14px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 700;
            display: inline-block;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .btn-slip:hover {
            background: #e07b00;
            box-shadow: 0 4px 10px rgba(255, 140, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="images/logo.png" alt="Logo" style="height: 65px; width: auto; display: block; object-fit: contain; filter: drop-shadow(0px 0px 8px rgba(255, 255, 255, 0.65));">
        </div>
        <div style="display:flex; align-items:center; gap:15px;">
            <div class="user-welcome">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></div>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
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
                <thead>
                    <tr>
                        <th>Payroll ID</th>
                        <th>Employee ID</th>
                        <th>Payroll Date</th>
                        <th>Payroll Amount</th>
                        <th>Payroll Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): 
                        $statusStr = strtolower(trim($row['Payroll_Status']));
                        if ($statusStr === 'paid' || $statusStr === 'success' || $statusStr === 'clear') {
                            $statusClass = 'status-paid';
                        } elseif ($statusStr === 'unpaid' || $statusStr === 'failed') {
                            $statusClass = 'status-unpaid';
                        } else {
                            $statusClass = 'status-pending';
                        }
                    ?>
                    <tr>
                        <td><strong>#<?php echo $row['Payroll_ID']; ?></strong></td>
                        <td><?php echo htmlspecialchars($row['Employee_ID']); ?></td>
                        <td><?php echo htmlspecialchars($row['Payroll_Date']); ?></td>
                        <td>RM <?php echo number_format($row['Payroll_Amount'], 2); ?></td>
                        <td>
                            <span class="status <?php echo $statusClass; ?>">
                                <?php echo htmlspecialchars($row['Payroll_Status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="generateslipEM.php?id=<?php echo $row['Payroll_ID']; ?>" target="_blank" class="btn-slip">
                                📄 View Slip
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    
                    <?php if(mysqli_num_rows($result) == 0): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #64748b; font-style: italic; border: none;">
                                No payroll records found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>