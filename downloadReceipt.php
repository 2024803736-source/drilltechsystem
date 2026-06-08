<?php
session_start();
include("database.php");

if(!isset($_SESSION['client_id']) || !isset($_GET['payment_id'])){
    header("Location: paymentCL.php");
    exit();
}

$payment_id = intval($_GET['payment_id']);
$client_id = $_SESSION['client_id'];

// Get payment + project + client details
$query = "
    SELECT p.*, pr.Project_Name, pr.Project_Location, pr.Project_Value, c.Client_Name 
    FROM payment p 
    JOIN project pr ON p.Project_ID = pr.Project_ID 
    JOIN client c ON pr.Client_ID = c.Client_ID 
    WHERE p.Payment_ID = $payment_id AND pr.Client_ID = $client_id
";

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if(!$row){
    die("<h2 style='color:red;text-align:center;margin-top:50px;'>Receipt not found or you don't have access.</h2>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #<?php echo $row['Payment_ID']; ?></title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 30px; 
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.65)), url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: #333;
        }
        .receipt { 
            max-width: 800px; 
            margin: auto; 
            border: 3px solid #003087; 
            padding: 40px; 
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
             text-align: center; 
             border-bottom: 4px solid #003087; 
             padding-bottom: 20px; 
             margin-bottom: 30px;
             }
        .logo { 
            font-size: 36px;
            color: #003087; 
            margin-bottom: 5px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0; }
        th, td {
             padding: 12px 15px; 
             text-align: left; 
             border-bottom: 1px solid #ddd;
             }
        th { 
            background: #003087; 
            color: white; 
        }
        .total { 
            font-size: 22px; 
            font-weight: bold; 
            text-align: right;
             margin-top: 30px; 
             color: #003087;
             }
        .footer { text-align: center;
         margin-top: 50px; 
         color: #666; 
         font-size: 14px;
         }
        .print-btn { 
            padding: 12px 30px; 
            background: #28a745; 
            color: white; 
            border: none; 
            border-radius: 50px; 
            font-size: 16px; 
            cursor: pointer;
            margin: 20px;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1 class="logo">🔧 DRILLTECH HDD</h1>
            <h2>OFFICIAL PAYMENT RECEIPT</h2>
            <p><strong>Receipt No: #<?php echo $row['Payment_ID']; ?></strong></p>
        </div>

        <table>
            <tr><th>Client Name</th><td><?php echo htmlspecialchars($row['Client_Name']); ?></td></tr>
            <tr><th>Project Name</th><td><?php echo htmlspecialchars($row['Project_Name']); ?></td></tr>
            <tr><th>Location</th><td><?php echo htmlspecialchars($row['Project_Location']); ?></td></tr>
            <tr><th>Payment Date</th><td><?php echo $row['Payment_Date'] . " " . $row['Payment_Time']; ?></td></tr>
            <tr><th>Payment Method</th><td><?php echo htmlspecialchars($row['Payment_Method']); ?></td></tr>
            <tr><th>Status</th><td><strong style="color:green;">COMPLETED</strong></td></tr>
        </table>

        <div class="total">
            Amount Paid: RM <?php echo number_format($row['Project_Value'] * 0.25, 2); ?> 
        </div>

        <div class="footer">
            <p>Thank you for your business with DrillTech HDD Company</p>
            <p>This is a computer-generated receipt.</p>
        </div>
    </div>

    <center>
        <button class="print-btn" onclick="window.print()">🖨️ Print / Save as PDF</button>
        <button class="print-btn" onclick="window.history.back()" style="background:#666;">Back</button>
    </center>
</body>
</html>