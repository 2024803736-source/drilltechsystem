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
    <title>Project - DrillTech Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            color: #222;
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
            margin-bottom: 25px;
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
        .status-ongoing   { color: #1a6bbf; font-weight: bold; }
        .status-completed { color: #2a7a2a; font-weight: bold; }
        .status-pending   { color: #aa5500; font-weight: bold; }

        /* ===== REPORT FORM ===== */
        .report-box {
            background: #dcdcdc;
            border: 1px solid #bbb;
            border-radius: 10px;
            padding: 25px;
        }
        .report-box h2 {
            margin-bottom: 15px;
            border-bottom: 2px solid #888;
            padding-bottom: 8px;
        }
        .report-box select {
            padding: 8px 12px;
            border-radius: 5px;
            border: 1px solid #bbb;
            background: #f0f0f0;
            font-size: 14px;
            margin: 0 10px;
        }
        .report-box button {
            padding: 8px 20px;
            background: #5a5a5a;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }
        .report-box button:hover { background: #3a3a3a; }

        a { color: #1a6bbf; }
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
            <a href="projectAD.php" class="active">📁 PROJECT</a>
            <a href="employeeAD.php">👷 EMPLOYEE</a>
        </div>

        <!-- Content -->
        <div class="content">

            <div class="box">
                <h2>Project List</h2>
                <table>
                    <tr>
                        <th>Project ID</th>
                        <th>Project Name</th>
                        <th>Client</th>
                        <th>Status</th>
                        <th>Location</th>
                        <th>Value (RM)</th>
                    </tr>
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM project ORDER BY Project_ID");
                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            $statusClass = "";
                            if($row['Project_Status'] === "On Going")      $statusClass = "status-ongoing";
                            elseif($row['Project_Status'] === "Completed")  $statusClass = "status-completed";
                            elseif($row['Project_Status'] === "Pending")    $statusClass = "status-pending";
                    ?>
                    <tr>
                        <td>
                            <?php if($row['Project_Status'] === "Pending"){ ?>
                                <a href="projectdetailsAD.php?id=<?php echo $row['Project_ID']; ?>">
                                    <?php echo $row['Project_ID']; ?>
                                </a>
                            <?php } else { ?>
                                <?php echo $row['Project_ID']; ?>
                            <?php } ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['Project_Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Client_ID']); ?></td>
                        <td class="<?php echo $statusClass; ?>"><?php echo $row['Project_Status']; ?></td>
                        <td><?php echo htmlspecialchars($row['Project_Location']); ?></td>
                        <td>RM<?php echo number_format($row['Project_Value'], 2); ?></td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='6'>No projects found.</td></tr>";
                    }
                    ?>
                </table>
            </div>

            <div class="report-box">
                <h2>Generate Project Report</h2>
                <form action="generateReportAD.php" method="get" target="_blank">
                    <label for="projectID">Select Project:</label>
                    <select name="id" id="projectID" required>
                        <option value="">-- Choose Project ID --</option>
                        <?php
                        $projList = mysqli_query($conn, "SELECT Project_ID, Project_Name FROM project ORDER BY Project_ID");
                        while($p = mysqli_fetch_assoc($projList)){
                            echo "<option value='".$p['Project_ID']."'>".$p['Project_ID']." - ".htmlspecialchars($p['Project_Name'])."</option>";
                        }
                        ?>
                    </select>
                    <button type="submit">Generate Report</button>
                </form>
            </div>

        </div>

    </div>

</body>
</html>