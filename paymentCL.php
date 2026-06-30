<?php
session_start();
include("database.php");

if(!isset($_SESSION['client_id'])){
    header("Location: loginCL.php");
    exit();
}

$client_id = $_SESSION['client_id'];
$client_name = $_SESSION['client_name'];

// Fetch payments
$payments = mysqli_query($conn, "
    SELECT p.*, pr.Project_Name, pr.Project_ID as ProjID 
    FROM payment p 
    JOIN project pr ON p.Project_ID = pr.Project_ID 
    WHERE pr.Client_ID = $client_id 
    ORDER BY p.Payment_Date DESC, p.Payment_ID DESC
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
                        url('backgroundCSC264.png') center/cover no-repeat fixed;
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
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 6px 14px;
            border-radius: 20px;
        }

        .sidebar {
            width: 240px;
            background: #003087;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            position: fixed;
            top: 64px; left: 0;
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
        
        .sidebar a:hover, .sidebar a.active { 
            color: #fff;
            background: #ff8c00; 
        }

        .content {
            margin-left: 260px;
            padding: 94px 30px 40px 30px;
        }

        .main-box {
            background: rgba(0, 0, 0, 0.65); 
            border: 1px solid rgba(0, 48, 135, 0.35);
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

        tr:hover td { background: rgba(255, 255, 255, 0.02); }
        
        th { 
            background: rgba(0, 48, 135, 0.4); 
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

        /* Receipt Button */
        .btn-download {
            background: rgba(0, 204, 102, 0.12);
            color: #00cc66;
            border: 1px solid rgba(0, 204, 102, 0.25);
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-download:hover {
            background: #00cc66;
            color: white;
        }

        /* Disabled button (Pending) */
        .btn-disabled {
            background: rgba(100, 100, 100, 0.2);
            color: #666;
            border: 1px solid rgba(100, 100, 100, 0.3);
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: not-allowed;
            pointer-events: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .action-buttons { text-align: center; margin-top: 30px; }

        .btn-make {
            background: linear-gradient(135deg, #ff8c00, #d97706);
            color: white;
            padding: 14px 32px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(255, 140, 0, 0.25);
            transition: all 0.2s ease;
        }

        .btn-make:hover {
            background: linear-gradient(135deg, #ffa033, #d97706);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(255, 140, 0, 0.4);
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="images/logo.png" alt="Logo" style="height: 65px; width: auto; display: block; object-fit: contain; filter: drop-shadow(0px 0px 8px rgba(255, 255, 255, 0.65));">
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
            <h2>Payment History</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Project ID</th>
                        <th>Project Name</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Method</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($payments)): ?>
                    <tr>
                        <td><strong>#<?php echo $row['Payment_ID']; ?></strong></td>
                        <td>#<?php echo $row['ProjID']; ?></td>
                        <td><?php echo htmlspecialchars($row['Project_Name']); ?></td>
                        <td>
                            <?php 
                            $status = $row['Payment_Status'];
                            $class = ($status == 'Completed') ? 'status-completed' : 'status-pending';
                            echo "<span class='status $class'>$status</span>";
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['Payment_Date']); ?></td>
                        <td><?php echo htmlspecialchars($row['Payment_Method']); ?></td>
                        <td>
                            <?php if($row['Payment_Status'] == 'Completed'): ?>
                                <a href="downloadReceipt.php?payment_id=<?php echo $row['Payment_ID']; ?>" class="btn-download">
                                    📥 Receipt
                                </a>
                            <?php else: ?>
                                <span class="btn-disabled">Pending</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="action-buttons">
                <a href="makePaymentCL.php" class="btn-make">Make New Payment</a>
            </div>
        </div>
    </div>
</body>
</html>