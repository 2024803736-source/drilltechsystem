<?php
session_start();
include("database.php");

if(!isset($_SESSION['client_id'])){
    header("Location: loginCL.php");
    exit();
}

$client_id = $_SESSION['client_id'];
$client_name = $_SESSION['client_name'];

/**
 * KEMASKINI QUERY UTK SENARAI PROJEK + STATUS BAYARAN:
 * Kita bermula daripada table 'project' (LEFT JOIN 'payment') supaya 
 * semua projek yang berstatus On Going/Completed akan SENTIASA keluar, 
 * walaupun projek itu belum dibayar lagi.
 */
$payments = mysqli_query($conn, "
    SELECT pr.Project_ID as ProjID, pr.Project_Name, pr.Project_Status,
           p.Payment_ID, p.Payment_Status, p.Payment_Date, p.Payment_Method
    FROM project pr
    LEFT JOIN payment p ON pr.Project_ID = p.Project_ID
    WHERE pr.Client_ID = $client_id 
      AND (pr.Project_Status = 'On Going' OR pr.Project_Status = 'Ongoing' OR pr.Project_Status = 'Completed')
    ORDER BY pr.Project_ID DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments - DrillTech HDD</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(rgba(0, 48, 135, 0.45), rgba(15, 20, 30, 0.9)), 
                        url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: #f8fafc;
            min-height: 100vh;
        }
        .header {
            background: #003087;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            padding: 0 30px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        .user-welcome {
            font-size: 14px; font-weight: 600; color: #fff;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 6px 14px; border-radius: 20px;
        }
        .sidebar {
            width: 240px; background: #003087; border-right: 1px solid rgba(255, 255, 255, 0.1);
            position: fixed; top: 64px; left: 0; height: calc(100vh - 64px); padding-top: 20px; z-index: 90;
        }
        .sidebar a {
            display: flex; align-items: center; padding: 14px 25px; color: rgba(255, 255, 255, 0.8);
            text-decoration: none; font-size: 14px; font-weight: 600; transition: all 0.2s ease; gap: 10px;
        }
        .sidebar a:hover, .sidebar a.active { color: #fff; background: #ff8c00; }
        .content { margin-left: 260px; padding: 94px 30px 40px 30px; }
        .main-box { background: rgba(0, 0, 0, 0.65); border: 1px solid rgba(0, 48, 135, 0.35); border-radius: 12px; padding: 28px; }
        .main-box h2 { font-size: 20px; font-weight: 700; margin-bottom: 20px; border-bottom: 2px solid #ff8c00; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 16px 14px; text-align: left; border-bottom: 1px solid rgba(255, 255, 255, 0.08); font-size: 14px; }
        th { background: rgba(0, 48, 135, 0.4); color: #ffcc00; text-transform: uppercase; font-size: 13px; }
        
        /* Badges */
        .status { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-block; text-transform: uppercase; }
        .status-completed { background: rgba(51, 153, 255, 0.12); color: #3399ff; border: 1px solid rgba(51, 153, 255, 0.25); }
        .status-unpaid { background: rgba(220, 53, 69, 0.15); color: #ff4d4d; border: 1px solid rgba(220, 53, 69, 0.3); }
        
        /* Buttons */
        .btn-download { background: rgba(0, 204, 102, 0.12); color: #00cc66; border: 1px solid rgba(0, 204, 102, 0.25); padding: 6px 14px; border-radius: 8px; text-decoration: none; font-weight: 600;}
        .btn-download:hover { background: #00cc66; color: white; }
        .btn-pay-now { background: #dc3545; color: white; padding: 6px 14px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 13px;}
        .btn-pay-now:hover { background: #bd2130; }
        
        .success-flash { background: rgba(40, 167, 69, 0.15); border: 1px solid rgba(40, 167, 69, 0.3); color: #2ecc71; padding: 14px; border-radius: 8px; text-align: center; margin-bottom: 20px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="images/logo.png" alt="Logo" style="height: 65px; width: auto; display: block;">
        </div>
        <div class="user-welcome">Welcome, <?php echo htmlspecialchars($client_name); ?></div>
    </div>

    <div class="sidebar">
        <a href="client.php">📊 DASHBOARD</a>
        <a href="projectCL.php">🔍 PROJECT</a>
        <a href="paymentCL.php" class="active">💰 PAYMENT</a>
    </div>

    <div class="content">
        <div class="main-box">
            <h2>Payment Status Overview</h2>
            
            <?php if(isset($_SESSION['payment_success'])): ?>
                <div class="success-flash">
                    🎉 <?php echo $_SESSION['payment_success']; ?>
                    <?php unset($_SESSION['payment_success']); ?>
                </div>
            <?php endif; ?>
            
            <table>
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Project ID</th>
                        <th>Project Name</th>
                        <th>Payment Status</th>
                        <th>Date</th>
                        <th>Method</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($payments)): ?>
                    <tr>
                        <td>
                            <strong><?php echo $row['Payment_ID'] ? "#".$row['Payment_ID'] : "-"; ?></strong>
                        </td>
                        <td>#<?php echo $row['ProjID']; ?></td>
                        <td><?php echo htmlspecialchars($row['Project_Name']); ?></td>
                        <td>
                            <?php 
                            if($row['Payment_ID']) {
                                echo "<span class='status status-completed'>".htmlspecialchars($row['Payment_Status'])."</span>";
                            } else {
                                echo "<span class='status status-unpaid'>Unpaid</span>";
                            }
                            ?>
                        </td>
                        <td><?php echo $row['Payment_Date'] ? htmlspecialchars($row['Payment_Date']) : "-"; ?></td>
                        <td><?php echo $row['Payment_Method'] ? htmlspecialchars($row['Payment_Method']) : "-"; ?></td>
                        <td>
                            <?php if($row['Payment_ID']): ?>
                                <a href="downloadReceipt.php?payment_id=<?php echo $row['Payment_ID']; ?>" class="btn-download">📥 Receipt</a>
                            <?php else: ?>
                                <a href="makePaymentCL.php?project_id=<?php echo $row['ProjID']; ?>" class="btn-pay-now">💳 Pay Now</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    
                    <?php if(mysqli_num_rows($payments) == 0): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; color: #64748b; font-style: italic;">No approved projects found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>