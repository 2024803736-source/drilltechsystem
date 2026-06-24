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
    <title>Receipt #<?php echo $row['Payment_ID']; ?> - DrillTech HDD</title>
    <style>
        /* Reset & Document Print Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(rgba(0, 48, 135, 0.45), rgba(15, 20, 30, 0.9)), 
                        url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: #334155;
            min-height: 100vh;
        }

        /* Top Action Bar - Menggunakan warna tema Client (#003087) */
        .top-bar {
            background: #003087;
            padding: 12px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        .top-bar span {
            color: white;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .top-bar button {
            padding: 8px 18px;
            background: #ff8c00; /* Warna oren tema client */
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            margin-left: 8px;
        }
        
        .top-bar button:hover {
            background: #e07b00;
        }

        /* Printable Document Sheet */
        .report {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            padding: 50px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
        }

        /* Center Watermark Logo */
        .report::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 350px;
            height: 350px;
            background: url('images/logo.png') no-repeat center;
            background-size: contain;
            opacity: 0.04;
            pointer-events: none;
            z-index: 0;
        }

        .report-content {
            position: relative;
            z-index: 10;
        }

        /* Letterhead logo */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #003087; /* Ditukar ke Biru Client */
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .company-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .company-logo {
            height: 55px;
            width: auto;
            object-fit: contain;
        }

        .company-name {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .report-title-section {
            text-align: right;
        }

        .report-title {
            font-size: 18px;
            font-weight: 800;
            color: #003087; /* Ditukar ke Biru Client */
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .guideline-text {
            font-size: 13px;
            font-style: italic;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 20px;
            background: #f8fafc;
            padding: 10px 14px;
            border-left: 3px solid #ff8c00; /* Kekal oren mengikut kod rujukan */
            border-radius: 0 4px 4px 0;
        }

        /* Headings */
        h2 {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 25px;
            margin-bottom: 12px;
            background: #f1f5f9;
            padding: 8px 12px;
            border-left: 4px solid #003087; /* Ditukar ke Biru Client */
            border-radius: 0 4px 4px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Table design */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th, td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #cbd5e1;
            font-size: 13.5px;
            color: #334155;
        }

        th {
            background: #f8fafc;
            color: #003087; /* Ditukar ke Biru Client */
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }

        .highlight-row {
            background-color: #f8fafc;
            font-weight: 700;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            font-size: 11px;
            color: #94a3b8;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }

        /* ===== PRINT STYLES ===== */
        @media print {
            body { background: none !important; color: black !important; }
            .top-bar { display: none !important; }
            .report {
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
                max-width: 100% !important;
            }
            .report::before { opacity: 0.05 !important; }
        }
    </style>
</head>
<body>

<div class="top-bar">
    <span>🔧 DRILLTECH — Official Payment Receipt</span>
    <div>
        <button onclick="window.print()">🖨 Print Receipt</button>
        <button onclick="window.history.back()" style="background:#7a7a7a;">✕ Back</button>
    </div>
</div>

<div class="report">
    <div class="report-content">
        
        <div class="report-header">
            <div class="company-info">
                <img src="images/logo.png" alt="Company Logo" class="company-logo" onerror="this.style.display='none';">
                <span class="company-name">DrillTech HDD Sdn. Bhd.</span>
            </div>
            <div class="report-title-section">
                <span class="report-title">Official Receipt</span>
            </div>
        </div>

        <p class="guideline-text">
            This receipt is officially generated by the DrillTech System as valid proof of payment for the transaction stated below.
        </p>

        <h2>Transaction Information</h2>
        <p class="guideline-text" style="background:none; border:none; padding:0; margin-bottom:12px;">
            Please check the transaction reference and payment metadata for future recording purposes.
        </p>
        <table>
            <tr>
                <th style="width: 25%;">Detail Label</th>
                <th>Information</th>
            </tr>
            <tr>
                <td><strong>Receipt No</strong></td>
                <td>#<?php echo htmlspecialchars($row['Payment_ID']); ?></td>
            </tr>
            <tr>
                <td><strong>Client Name</strong></td>
                <td><?php echo htmlspecialchars($row['Client_Name']); ?></td>
            </tr>
            <tr>
                <td><strong>Project Name</strong></td>
                <td><?php echo htmlspecialchars($row['Project_Name']); ?></td>
            </tr>
            <tr>
                <td><strong>Project Location</strong></td>
                <td><?php echo htmlspecialchars($row['Project_Location']); ?></td>
            </tr>
            <tr>
                <td><strong>Payment Date & Time</strong></td>
                <td><?php echo htmlspecialchars($row['Payment_Date']) . " " . htmlspecialchars($row['Payment_Time']); ?></td>
            </tr>
            <tr>
                <td><strong>Payment Method</strong></td>
                <td><?php echo htmlspecialchars($row['Payment_Method']); ?></td>
            </tr>
            <tr>
                <td><strong>Status</strong></td>
                <td><strong style="color:green; text-transform:uppercase;">COMPLETED</strong></td>
            </tr>
            <tr class="highlight-row" style="background: #f1f5f9;">
                <td>Amount Paid (RM)</td>
                <td><strong>RM <?php echo number_format($row['Project_Value'], 2); ?></strong></td>
            </tr>
        </table>

        <h2>Verification Details</h2>
        <div style="font-size: 13.5px; line-height: 1.6; color: #334155; margin-left: 12px; margin-bottom: 25px;">
            <p><strong>System Note:</strong> Thank you for your business with DrillTech HDD Company.</p>
            <p><strong>Generation Type:</strong> This is a secure computer-generated receipt. No physical signature is required.</p>
        </div>

        <div class="footer">
            DRILLTECH HDD Sdn. Bhd. &copy; All Rights Reserved.
        </div>
    </div>
</div>

</body>
</html>