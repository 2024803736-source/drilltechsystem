<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
if(!isset($_SESSION['admin_id'])){
    header("Location: loginAD.php");
    exit();
}
include("database.php");

if(!isset($_GET['id'])) die("No project selected.");

$projectID  = $_GET['id'];
$admin_name = $_SESSION['admin_name'] ?? 'Admin';

// Fetch main project details
$project = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM project WHERE Project_ID='$projectID'"));
if(!$project) die("Project not found.");

$client  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM client WHERE Client_ID='".$project['Client_ID']."'"));

// Tarik Tarikh Mula & Tarikh Tamat daripada table assigned_employee
$dateQuery = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT MIN(ProjectEmp_StartD) as start_date, MAX(ProjectEmp_EndD) as end_date 
    FROM assigned_employee 
    WHERE Project_ID='$projectID'
"));
$startDate = !empty($dateQuery['start_date']) ? $dateQuery['start_date'] : 'N/A';
$endDate   = !empty($dateQuery['end_date']) ? $dateQuery['end_date'] : 'N/A';

// Tarik satu Site Engineer sahaja
$engineer = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT e.Employee_Name, e.Employee_Contact 
    FROM assigned_employee ae 
    JOIN employee e ON ae.Employee_ID=e.Employee_ID 
    WHERE ae.Project_ID='$projectID' AND e.Employee_Position='Site Engineer' 
    LIMIT 1
"));

// Tarik satu Site Supervisor sahaja
$supervisor = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT e.Employee_Name, e.Employee_Contact 
    FROM assigned_employee ae 
    JOIN employee e ON ae.Employee_ID=e.Employee_ID 
    WHERE ae.Project_ID='$projectID' AND e.Employee_Position='Site Supervisor' 
    LIMIT 1
"));

// Kira jumlah General Worker yang terlibat
$workerCount = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM assigned_employee ae 
    JOIN employee e ON ae.Employee_ID=e.Employee_ID 
    WHERE ae.Project_ID='$projectID' AND e.Employee_Position='General Worker'
"))['total'] ?? 0;

// Tarik SEMUA equipment yang digunakan untuk projek ini
$equipments = mysqli_query($conn, "
    SELECT eq.Equipment_ID, eq.Equipment_Name, eu.Equipment_Duration 
    FROM equipment_usage eu 
    JOIN equipment eq ON eu.Equipment_ID=eq.Equipment_ID 
    WHERE eu.Project_ID='$projectID'
");

// Fetch financial transactions
$payments = mysqli_query($conn, "SELECT * FROM payment WHERE Project_ID='$projectID'");

$amountPaid = 0;
$paymentRecords = [];
while($p = mysqli_fetch_assoc($payments)) {
    // Sbb tiada column Payment_Amount, kita gunakan Project_Value sebagai amount jika status Completed
    $p['Payment_Amount'] = (strtolower($p['Payment_Status']) == 'completed') ? $project['Project_Value'] : 0.00;
    
    $paymentRecords[] = $p;
    if(strtolower($p['Payment_Status']) == 'completed') {
        $amountPaid = $project['Project_Value'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Management Report - DrillTech HDD</title>
    <style>
        /* Reset & Document Print Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.75)), 
                        url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: #334155;
            min-height: 100vh;
        }

        /* Top Action Bar (#4a4a4a grey) */
        .top-bar {
            background: #4a4a4a;
            padding: 12px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        
        .top-bar span {
            color: white;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .top-bar button {
            padding: 8px 18px;
            background: #7a7a7a;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            margin-left: 8px;
        }
        
        .top-bar button:hover {
            background: #5a5a5a;
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
            opacity: 0.04; /* Watermark */
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
            border-bottom: 2px solid #334155;
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
            color: #475569;
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
            border-left: 3px solid #ff8c00;
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
            border-left: 4px solid #475569;
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
            color: #475569;
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
    <span>🔧 DRILLTECH — Printable Project Summary</span>
    <div>
        <button onclick="window.print()">🖨 Print Report</button>
        <button onclick="window.close()">✕ Back</button>
    </div>
</div>

<div class="report">
    <div class="report-content">
        <!-- Header Section -->
        <div class="report-header">
            <div class="company-info">
                <img src="images/logo.png" alt="Company Logo" class="company-logo" onerror="this.style.display='none';">
                <span class="company-name">DrillTech HDD Sdn. Bhd.</span>
            </div>
            <div class="report-title-section">
                <span class="report-title">Project Management Report</span>
            </div>
        </div>

        <p class="guideline-text">
            This report is generated by the DrillTech Management System to provide a consolidated overview of project activities, financial records, and resource allocation.
        </p>

        <!-- Project Overview -->
        <h2>Project Overview</h2>
        <p class="guideline-text" style="background:none; border:none; padding:0; margin-bottom:12px;">
            This section summarizes all active and completed projects, ensuring administrators can monitor progress and deadlines effectively.
        </p>
        <table>
            <tr>
                <th style="width: 25%;">Detail Label</th>
                <th>Information</th>
            </tr>
            <tr>
                <td><strong>Project ID & Name</strong></td>
                <td>#<?php echo htmlspecialchars($project['Project_ID']); ?> - <?php echo htmlspecialchars($project['Project_Name']); ?></td>
            </tr>
            <tr>
                <td><strong>Client Name</strong></td>
                <td><?php echo $client ? htmlspecialchars($client['Client_Name']) : '#'.htmlspecialchars($project['Client_ID']); ?></td>
            </tr>
            <tr>
                <td><strong>Project Status</strong></td>
                <td><?php echo htmlspecialchars($project['Project_Status']); ?></td>
            </tr>
            <tr>
                <td><strong>Start & Due Date</strong></td>
                <td><?php echo htmlspecialchars($startDate) . " to " . htmlspecialchars($endDate); ?></td>
            </tr>
            <tr class="highlight-row">
                <td>Project Value (RM)</td>
                <td>RM <?php echo number_format($project['Project_Value'], 2); ?></td>
            </tr>
        </table>

        <!-- Key Staff Assignment -->
        <h2>Assigned Staff Summary</h2>
        <p class="guideline-text" style="background:none; border:none; padding:0; margin-bottom:12px;">
            Key staff assignments are highlighted to ensure accountability and provide quick references for communication.
        </p>
        <table>
            <thead>
                <tr>
                    <th>Role Position</th>
                    <th>Staff Name</th>
                    <th>Contact Info</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Site Engineer</strong></td>
                    <td><?php echo $engineer ? htmlspecialchars($engineer['Employee_Name']) : 'Unassigned'; ?></td>
                    <td><?php echo $engineer ? htmlspecialchars($engineer['Employee_Contact']) : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td><strong>Site Supervisor</strong></td>
                    <td><?php echo $supervisor ? htmlspecialchars($supervisor['Employee_Name']) : 'Unassigned'; ?></td>
                    <td><?php echo $supervisor ? htmlspecialchars($supervisor['Employee_Contact']) : 'N/A'; ?></td>
                </tr>
                <tr class="highlight-row">
                    <td>General Workers Count</td>
                    <td colspan="2"><?php echo $workerCount; ?> Workers Assigned</td>
                </tr>
            </tbody>
        </table>

        <!-- Financial Records -->
        <h2>Financial Records</h2>
        <p class="guideline-text" style="background:none; border:none; padding:0; margin-bottom:12px;">
            Financial records provide transparency by showing payment status, methods, and project values against received amounts.
        </p>
        <table>
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Payment Status</th>
                    <th>Payment Date & Method</th>
                    <th>Amount Paid</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($paymentRecords as $pay): ?>
                <tr>
                    <td>#<?php echo $pay['Payment_ID']; ?></td>
                    <td><?php echo htmlspecialchars($pay['Payment_Status']); ?></td>
                    <td><?php echo htmlspecialchars($pay['Payment_Date']) . " (" . htmlspecialchars($pay['Payment_Method']) . ")"; ?></td>
                    <td>RM <?php echo number_format($pay['Payment_Amount'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($paymentRecords)): ?>
                <tr>
                    <td colspan="4" style="text-align: center; color: #64748b; font-style: italic;">No payments captured.</td>
                </tr>
                <?php endif; ?>
                <tr class="highlight-row">
                    <td colspan="3" style="text-align: right;">Total Amount Received:</td>
                    <td>RM <?php echo number_format($amountPaid, 2); ?></td>
                </tr>
                <tr class="highlight-row" style="background: #f1f5f9;">
                    <td colspan="3" style="text-align: right;">Outstanding Balance (Project Value vs Paid):</td>
                    <?php 
                    $balance = $project['Project_Value'] - $amountPaid; 
                    echo "<td>RM " . number_format($balance, 2) . "</td>";
                    ?>
                </tr>
            </tbody>
        </table>

        <!-- Equipment Usage Summary -->
        <h2>Equipment Usage Summary</h2>
        <p class="guideline-text" style="background:none; border:none; padding:0; margin-bottom:12px;">
            Equipment usage is summarized to assist in resource planning and maintenance scheduling.
        </p>
        <table>
            <thead>
                <tr>
                    <th>Equipment ID</th>
                    <th>Machinery Name</th>
                    <th>Allocated Duration / Unit</th>
                    <th>Linked Project ID</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $eqCount = 0;
                mysqli_data_seek($equipments, 0); // reset pointer
                while($eq = mysqli_fetch_assoc($equipments)): 
                    $eqCount++;
                ?>
                <tr>
                    <td>#<?php echo htmlspecialchars($eq['Equipment_ID']); ?></td>
                    <td><?php echo htmlspecialchars($eq['Equipment_Name']); ?></td>
                    <td><?php echo htmlspecialchars($eq['Equipment_Duration']); ?></td>
                    <td>#<?php echo htmlspecialchars($project['Project_ID']); ?></td>
                </tr>
                <?php endwhile; ?>
                <?php if($eqCount == 0): ?>
                <tr>
                    <td colspan="4" style="text-align: center; color: #64748b; font-style: italic;">No equipment logged for this project.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Report Metadata -->
        <h2>Report Details</h2>
        <p class="guideline-text" style="background:none; border:none; padding:0; margin-bottom:12px;">
            Reports can be exported in PDF format for management review and documentation purposes.
        </p>
        <div style="font-size: 13.5px; line-height: 1.6; color: #334155; margin-left: 12px; margin-bottom: 25px;">
            <p><strong>Generated By:</strong> <?php echo htmlspecialchars($admin_name); ?> (ID: <?php echo htmlspecialchars($_SESSION['admin_id']); ?>)</p>
            <p><strong>Time:</strong> <?php date_default_timezone_set('Asia/Kuala_Lumpur'); echo date("d M Y, h:i A"); ?></p>
        </div>

        <div class="footer">
            DRILLTECH HDD Sdn. Bhd. &copy; All Rights Reserved.
        </div>
    </div>
</div>

</body>
</html>