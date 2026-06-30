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


if (isset($_GET['action']) && $_GET['action'] === 'complete' && isset($_GET['project_id'])) {
    $projectID = mysqli_real_escape_string($conn, $_GET['project_id']);
    
    // Kemaskini status projek dalam database
    $updateQuery = "UPDATE project SET Project_Status = 'Completed' WHERE Project_ID = '$projectID'";
    
    if (mysqli_query($conn, $updateQuery)) {
        // Berjaya kemaskini, refresh halaman tanpa parameter GET
        header("Location: projectAD.php");
        exit();
    } else {
        echo "<script>alert('Gagal mengemas kini status projek.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project - DrillTech Admin</title>
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

        /* ===== HEADER ===== */
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

        /* ===== LAYOUT WRAPPER ===== */
        .wrapper {
            display: flex;
            margin-top: 60px;
        }

        /* ===== SIDEBAR ===== */
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
        .box, .report-box {
            background: rgba(45, 45, 45, 0.85); 
            border: 1px solid rgba(120, 120, 120, 0.25);
            border-radius: 12px;
            padding: 28px;
            margin-bottom: 25px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
        }

        .box h2, .report-box h2 {
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
            vertical-align: middle;
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

        /* ===== STATUS LABELS & BUTTONS ===== */
        .status-completed { color: #34d399; font-weight: bold; }
        
        /* Badges untuk Payment Status */
        .pay-status { padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 700; display: inline-block; text-transform: uppercase; }
        .pay-paid { background: rgba(52, 211, 153, 0.15); color: #34d399; border: 1px solid rgba(52, 211, 153, 0.3); }
        .pay-unpaid { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }

        .btn-pending-active { 
            display: inline-block;
            padding: 6px 16px;
            background: #ffcc00; 
            color: #1e1e1e !important; 
            font-weight: 700; 
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 6px;
            text-decoration: none !important;
            box-shadow: 0 2px 8px rgba(255, 204, 0, 0.3);
            transition: all 0.2s ease-in-out;
            border: 1px solid transparent;
        }

        .btn-pending-active:hover {
            background: #ffea00;
            box-shadow: 0 4px 15px rgba(255, 204, 0, 0.5);
            transform: translateY(-1.5px);
        }

        .btn-ongoing-active {
            display: inline-block;
            padding: 6px 16px;
            background: #3b82f6; 
            color: white !important;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 6px;
            text-decoration: none !important;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
            transition: all 0.2s ease-in-out;
            border: 1px solid transparent;
        }

        .btn-ongoing-active:hover {
            background: #60a5fa;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.5);
            transform: translateY(-1.5px);
        }

        /* ===== REPORT FORM ===== */
        .report-box form {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .report-box label {
            font-size: 14px;
            font-weight: 600;
            color: #cbd5e1;
        }

        .report-box select {
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: rgba(0, 0, 0, 0.3);
            color: white;
            font-size: 14px;
            outline: none;
            min-width: 250px;
        }

        select option {
            background-color: #282828;
            color: white;
        }

        .report-box button {
            padding: 12px 24px;
            background: #ff8c00;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(255, 140, 0, 0.2);
        }

        .report-box button:hover {
            background: #e07b00;
            transform: translateY(-1px);
        }

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
        <div class="header-welcome">Welcome, <?php echo htmlspecialchars($admin_name); ?></div>
    </div>
    
    <div class="wrapper">

        <div class="sidebar">
            <a href="admin.php">📊 DASHBOARD</a>
            <a href="projectAD.php" class="active">📁 PROJECT</a>
            <a href="employeeAD.php">👷 EMPLOYEE</a>
        </div>

        <div class="content">

            <div class="box">
                <h2>Project List</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Project ID</th>
                            <th>Project Name</th>
                            <th>Client ID</th>
                            <th>Project Status</th>
                            <th>Payment Status</th>
                            <th>Location</th>
                            <th>Value (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Menggunakan LEFT JOIN untuk menarik info dari table payment
                        $query = "SELECT pr.*, p.Payment_ID, p.Payment_Status 
                                  FROM project pr 
                                  LEFT JOIN payment p ON pr.Project_ID = p.Project_ID 
                                  ORDER BY pr.Project_ID";
                                  
                        $result = mysqli_query($conn, $query);
                        if(mysqli_num_rows($result) > 0){
                            while($row = mysqli_fetch_assoc($result)){
                                $statusClass = "";
                                if($row['Project_Status'] === "Completed") $statusClass = "status-completed";
                        ?>
                        <tr>
                            <td><strong>#<?php echo $row['Project_ID']; ?></strong></td>
                            <td><?php echo htmlspecialchars($row['Project_Name']); ?></td>
                            <td>#<?php echo htmlspecialchars($row['Client_ID']); ?></td>
                            
                            <td>
                                <?php if($row['Project_Status'] === "Pending"){ ?>
                                    <a href="projectdetailsAD.php?id=<?php echo $row['Project_ID']; ?>" class="btn-pending-active">
                                        Pending
                                    </a>
                                <?php } elseif($row['Project_Status'] === "On Going" || $row['Project_Status'] === "Ongoing"){ ?>
                                    <a href="projectAD.php?action=complete&project_id=<?php echo $row['Project_ID']; ?>" 
                                       class="btn-ongoing-active" 
                                       onclick="return confirm('Are you sure you want to update Project #<?php echo $row['Project_ID']; ?> status to Completed?');">
                                        On Going
                                    </a>
                                <?php } else { ?>
                                    <span class="<?php echo $statusClass; ?>"><?php echo $row['Project_Status']; ?></span>
                                <?php } ?>
                            </td>
                            
                            <td>
                                <?php 
                                if($row['Project_Status'] === "Pending") {
                                    // Jika projek belum diaccept (Pending), tiada status bayaran keluar
                                    echo "<span style='color: #64748b;'>-</span>";
                                } else {
                                    // Jika projek sudah On Going atau Completed, baru semak status bayaran
                                    if(!empty($row['Payment_ID'])) {
                                        echo "<span class='pay-status pay-paid'>Paid</span>";
                                    } else {
                                        echo "<span class='pay-status pay-unpaid'>Unpaid</span>";
                                    }
                                }
                                ?>
                            </td>
                            
                            <td><?php echo htmlspecialchars($row['Project_Location']); ?></td>
                            <td>RM <?php echo number_format($row['Project_Value'], 2); ?></td>
                        </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='7' style='text-align: center; color: #64748b; font-style: italic; border: none;'>No projects found.</td></tr>";
                        }
                        ?>
                    </tbody>
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
                            echo "<option value='".$p['Project_ID']."'>#".$p['Project_ID']." - ".htmlspecialchars($p['Project_Name'])."</option>";
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