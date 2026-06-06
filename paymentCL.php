<?php
session_start();
include("database.php");

if(!isset($_SESSION['client_id'])){
    header("Location: loginCL.php");
    exit();
}

$client_id = $_SESSION['client_id'];
$client_name = $_SESSION['client_name'];

// Fetch all payments
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
            font-family: Arial, sans-serif;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.65)), url('ss1.jpg') center/cover no-repeat fixed;
            color: white;
            min-height: 100vh;
        }
        .header {
            background: #003087;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo {
            display: flex;
            align-items: center;
            font-size: 26px;
            font-weight: bold;
        }
        .sidebar {
            width: 240px;
            background: #003087;
            position: fixed;
            height: 100vh;
            padding-top: 20px;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
            gap: 10px;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #ff8c00;
        }
        .content {
            margin-left: 260px;
            padding: 30px;
        }
        .main-box {
            background: rgba(0,0,0,0.8);
            border-radius: 12px;
            padding: 25px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        th {
            background: #002266;
            color: #ffcc00;
        }
        .status-completed {
            background: #28a745;
            color: white;
            padding: 6px 18px;
            border-radius: 20px;
            font-weight: bold;
        }
        .status-pending {
            background: #ffc107;
            color: black;
            padding: 6px 18px;
            border-radius: 20px;
            font-weight: bold;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 30px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-download {
            background: linear-gradient(135deg, #28a745, #34d058);
            color: white;
        }
        .action-buttons {
            text-align: center;
            margin-top: 30px;
        }
        .btn-make {
            background: linear-gradient(135deg, #007bff, #00aaff);
            color: white;
            padding: 14px 32px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <span style="font-size:32px;">🔧</span> DRILLTECH
        </div>
        <div>Welcome, <?php echo htmlspecialchars($client_name); ?></div>
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
                <tr>
                    <th>Payment ID</th>
                    <th>Project ID</th>
                    <th>Project Name</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Method</th>
                    <th>Action</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($payments)): ?>
                <tr>
                    <td><?php echo $row['Payment_ID']; ?></td>
                    <td><?php echo $row['ProjID']; ?></td>
                    <td><?php echo htmlspecialchars($row['Project_Name']); ?></td>
                    <td>
                        <?php if($row['Payment_Status'] == 'Completed'): ?>
                            <span class="status-completed">Completed</span>
                        <?php else: ?>
                            <span class="status-pending">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $row['Payment_Date']; ?></td>
                    <td><?php echo htmlspecialchars($row['Payment_Method']); ?></td>
                    <td>
                        <a href="downloadReceipt.php?payment_id=<?php echo $row['Payment_ID']; ?>" class="btn btn-download">
                            📥 Receipt
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>

            <!-- Make Payment Button -->
            <div class="action-buttons">
                <a href="makePaymentCL.php">
                    <button class="btn btn-make">💳 Make New Payment</button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>