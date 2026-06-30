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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee - DrillTech Admin</title>
    <style>
        /* Reset & Base Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(rgba(74, 74, 74, 0.45), rgba(15, 15, 15, 0.95)), 
                        url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: #f8fafc;
            min-height: 100vh;
        }

        /* ===== HEADER (#4a4a4a grey) ===== */
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

        /* ===== LAYOUT WRAPPER ===== */
        .wrapper {
            display: flex;
            margin-top: 60px;
        }

        /* ===== SIDEBAR (#5a5a5a grey) ===== */
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

        /* ===== CONTENT PANEL ===== */
        .content {
            flex: 1;
            margin-left: 240px;
            padding: 35px 30px;
        }

        /* ===== BOX/CARDS ===== */
        .box {
            background: rgba(45, 45, 45, 0.85); 
            border: 1px solid rgba(120, 120, 120, 0.25);
            border-radius: 12px;
            padding: 28px;
            margin-bottom: 25px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
        }

        .box h2 {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
            border-bottom: 2px solid #ff8c00;
            padding-bottom: 10px;
            color: #fff;
        }

        /* ===== TABLE ===== */
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
            background: rgba(130, 124, 124, 0.4);
            color: #ffcc00;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ===== STATUS LABELS ===== */
        .status-paid    { color: #34d399; font-weight: bold; }
        .status-unpaid  { color: #f87171; font-weight: bold; }

        a {
            color: #60a5fa;
            text-decoration: none;
            font-weight: 700;
            transition: color 0.2s;
        }
        
        a:hover {
            color: #93c5fd;
            text-decoration: underline;
        }
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
            <a href="admin.php">📊 DASHBOARD</a>
            <a href="projectAD.php">📁 PROJECT</a>
            <a href="employeeAD.php" class="active">👷 EMPLOYEE</a>
        </div>

        <div class="content">
            <div class="box">
                <h2>Employee List with Payroll</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Contact</th>
                            <th>Payroll ID</th>
                            <th>Amount (RM)</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result2 = mysqli_query($conn, "SELECT e.Employee_ID, e.Employee_Name, e.Employee_Position, e.Employee_Contact,
                                                                 pr.Payroll_ID, pr.Payroll_Amount, pr.Payroll_Status, pr.Payroll_Date, pr.Payroll_Type
                                                          FROM employee e
                                                          LEFT JOIN payroll pr ON e.Employee_ID = pr.Employee_ID
                                                          ORDER BY e.Employee_ID, pr.Payroll_Date");

                        if(mysqli_num_rows($result2) > 0){
                            while($row = mysqli_fetch_assoc($result2)){
                                $statusClass = ($row['Payroll_Status'] == "Paid") ? "status-paid" : "status-unpaid";
                        ?>
                        <tr>
                            <td>#<?php echo htmlspecialchars($row['Employee_ID']); ?></td>
                            <td><?php echo htmlspecialchars($row['Employee_Name']); ?></td>
                            <td><?php echo htmlspecialchars($row['Employee_Position']); ?></td>
                            <td><?php echo htmlspecialchars($row['Employee_Contact']); ?></td>
                            <td><?php echo $row['Payroll_ID'] ? "#".$row['Payroll_ID'] : '-'; ?></td>
                            <td>RM <?php echo number_format($row['Payroll_Amount'] ?? 0, 2); ?></td>
                            <td class="<?php echo $statusClass; ?>"><?php echo $row['Payroll_Status'] ?? '-'; ?></td>
                            <td><?php echo $row['Payroll_Date'] ?? '-'; ?></td>
                            <td><?php echo htmlspecialchars($row['Payroll_Type'] ?? '-'); ?></td>
                        </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='9' style='text-align: center; color: #64748b; font-style: italic; border: none;'>No data found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>