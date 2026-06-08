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

$query = "SELECT e.Employee_ID, e.Employee_Name, e.Employee_Position, e.Employee_Contact,
                 pr.Payroll_ID, pr.Payroll_Amount, pr.Payroll_Status, pr.Payroll_Date, pr.Payroll_Type
          FROM employee e
          LEFT JOIN payroll pr ON e.Employee_ID = pr.Employee_ID
          ORDER BY e.Employee_ID, pr.Payroll_Date";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee - DrillTech Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.65)), url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: black;
            min-height: 100vh;
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
            margin-top: 60px;
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

        /* ===== BOX ===== */
        .box {
            background: #dcdcdc;
            border: 1px solid #bbb;
            border-radius: 10px;
            padding: 25px;
        }
        .box h2 {
            margin-bottom: 15px;
            border-bottom: 2px solid #888;
            padding-bottom: 8px;
        }

        /* ===== TABLE ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: #f0f0f0;
        }
        th, td {
            border: 1px solid #bbb;
            padding: 10px;
            text-align: center;
        }
        th {
            background: #5a5a5a;
            color: white;
        }
        tr:hover { background: #e0e0e0; }

        /* ===== STATUS ===== */
        .status-paid   { color: #2a7a2a; font-weight: bold; }
        .status-unpaid { color: #aa2222; font-weight: bold; }
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
            <a href="admin.php">📊 DASHBOARD</a>
            <a href="projectAD.php">📁 PROJECT</a>
            <a href="employeeAD.php" class="active">👷 EMPLOYEE</a>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="box">
                <h2>Employee List with Payroll</h2>
                <table>
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
                    <?php
                    // re-run query untuk pastikan data fresh
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
                        <td><?php echo htmlspecialchars($row['Employee_ID']); ?></td>
                        <td><?php echo htmlspecialchars($row['Employee_Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Employee_Position']); ?></td>
                        <td><?php echo htmlspecialchars($row['Employee_Contact']); ?></td>
                        <td><?php echo htmlspecialchars($row['Payroll_ID'] ?? '-'); ?></td>
                        <td>RM<?php echo number_format($row['Payroll_Amount'] ?? 0, 2); ?></td>
                        <td class="<?php echo $statusClass; ?>"><?php echo $row['Payroll_Status'] ?? '-'; ?></td>
                        <td><?php echo $row['Payroll_Date'] ?? '-'; ?></td>
                        <td><?php echo htmlspecialchars($row['Payroll_Type'] ?? '-'); ?></td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='9'>No data found.</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>

    </div>

</body>
</html>