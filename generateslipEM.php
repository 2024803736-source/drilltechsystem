<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
if(!isset($_SESSION['employee_id'])){
    header("Location: loginEM.php");
    exit();
}
include("database.php");

if(!isset($_GET['id'])) die("No payroll records selected.");

$payrollID = $_GET['id'];
$employeeID = $_SESSION['employee_id'];

// Fetch payroll details bersama maklumat pekerja
$query = "SELECT p.*, e.Employee_Name, e.Employee_Position, e.Employee_Contact 
          FROM payroll p 
          JOIN employee e ON p.Employee_ID = e.Employee_ID 
          WHERE p.Payroll_ID = '$payrollID' AND p.Employee_ID = '$employeeID'";

$payrollRes = mysqli_query($conn, $query);
$payroll = mysqli_fetch_assoc($payrollRes);

if(!$payroll) die("Payroll record not found or unauthorized access.");

// Mocking standard earnings/deductions variables based on DrillTech guidelines
// Anda boleh tukar variable ini dengan dynamic data sekiranya table anda mempunyai column ini
$basicPay = $payroll['Payroll_Amount']; 
$allowance = 0.00; 
$grossPay = $basicPay + $allowance;

$epf_employee = $grossPay * 0.11; // Contoh simulasi potongan 11% EPF
$socso_employee = 19.25;          // Contoh nilai statik SOCSO
$eis_employee = 7.70;             // Contoh nilai statik EIS
$totalDeduction = $epf_employee + $socso_employee + $eis_employee;

$netPay = $grossPay - $totalDeduction;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Payslip - DrillTech HDD</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.75)), 
                        url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: #334155;
            min-height: 100vh;
        }

        /* Top Action Bar Grey */
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

        /* Printable Payslip Sheet */
        .report {
            max-width: 850px;
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
            opacity: 0.03;
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
            padding-bottom: 15px;
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
        }

        .report-title-section {
            text-align: right;
        }

        .report-title {
            font-size: 18px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
        }

        /* Information Grid Table */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 6px 10px;
            font-size: 13px;
            border: none;
        }

        /* Breakdown Table Split Section */
        .breakdown-container {
            display: flex;
            width: 100%;
            border: 1px solid #cbd5e1;
            margin-bottom: 20px;
        }

        .breakdown-box {
            width: 50%;
        }

        .breakdown-box:first-child {
            border-right: 1px solid #cbd5e1;
        }

        .breakdown-title {
            background: #f1f5f9;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #cbd5e1;
            color: #1e293b;
        }

        .breakdown-table {
            width: 100%;
            border-collapse: collapse;
        }

        .breakdown-table td {
            padding: 8px 12px;
            font-size: 13px;
            border-bottom: 1px solid #f1f5f9;
        }

        .text-right {
            text-align: right;
        }

        /* Summary Total Styles */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .summary-table td {
            padding: 10px 12px;
            font-size: 14px;
            font-weight: bold;
            border-top: 1px solid #cbd5e1;
            border-bottom: 2px double #334155;
        }

        .footer-sign {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
            padding: 0 20px;
        }

        .sign-box {
            width: 200px;
            text-align: center;
            font-size: 13px;
            border-top: 1px solid #334155;
            padding-top: 5px;
        }

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
            .report::before { opacity: 0.04 !important; }
        }
    </style>
</head>
<body>

<div class="top-bar">
    <span></span>
    <div>
        <button onclick="window.print()">🖨 Print Payslip</button>
        <button onclick="window.close()">✕ Close Sheet</button>
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
                <span class="report-title">Payslip</span>
                <p style="font-size:12px; color:#64748b;">PAYMENT DATE: <?php echo htmlspecialchars($payroll['Payroll_Date']); ?></p>
            </div>
        </div>

        <table class="info-table">
            <tr>
                <td style="width: 18%;"><strong>Employee No.</strong></td>
                <td style="width: 32%;">: #<?php echo htmlspecialchars($payroll['Employee_ID']); ?></td>
                <td style="width: 18%;"><strong>Position</strong></td>
                <td>: <?php echo htmlspecialchars($payroll['Employee_Position']); ?></td>
            </tr>
            <tr>
                <td><strong>Name</strong></td>
                <td>: <?php echo htmlspecialchars($payroll['Employee_Name']); ?></td>
                <td><strong>Payroll ID</strong></td>
                <td>: #<?php echo htmlspecialchars($payroll['Payroll_ID']); ?></td>
            </tr>
            <tr>
                <td><strong>Contact Info</strong></td>
                <td>: <?php echo htmlspecialchars($payroll['Employee_Contact']); ?></td>
                <td><strong>Status</strong></td>
                <td>: <span style="text-transform: uppercase; font-weight: bold; color: green;"><?php echo htmlspecialchars($payroll['Payroll_Status']); ?></span></td>
            </tr>
        </table>

        <div class="breakdown-container">
            <div class="breakdown-box">
                <div class="breakdown-title">Earnings / Income</div>
                <table class="breakdown-table">
                    <tr>
                        <td>Basic Pay</td>
                        <td class="text-right">RM <?php echo number_format($basicPay, 2); ?></td>
                    </tr>
                    <tr>
                        <td>Allowance / Claims</td>
                        <td class="text-right">RM <?php echo number_format($allowance, 2); ?></td>
                    </tr>
                    <tr><td>&nbsp;</td><td></td></tr>
                    <tr><td>&nbsp;</td><td></td></tr>
                </table>
            </div>

            <div class="breakdown-box">
                <div class="breakdown-title">Deduction</div>
                <table class="breakdown-table">
                    <tr>
                        <td>Employee EPF (11%)</td>
                        <td class="text-right">RM <?php echo number_format($epf_employee, 2); ?></td>
                    </tr>
                    <tr>
                        <td>Employee SOCSO</td>
                        <td class="text-right">RM <?php echo number_format($socso_employee, 2); ?></td>
                    </tr>
                    <tr>
                        <td>Employee EIS</td>
                        <td class="text-right">RM <?php echo number_format($eis_employee, 2); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <table class="summary-table">
            <tr style="background-color: #f8fafc;">
                <td style="width: 25%;">GROSS PAY</td>
                <td class="text-right" style="width: 25%; color: #1e293b;">RM <?php echo number_format($grossPay, 2); ?></td>
                <td style="width: 25%; padding-left: 20px;">TOTAL DEDUCTION</td>
                <td class="text-right" style="width: 25%; color: #ef4444;">RM <?php echo number_format($totalDeduction, 2); ?></td>
            </tr>
            <tr style="font-size: 16px; background: #f1f5f9;">
                <td colspan="2" class="text-right">NET PAY (RM) :</td>
                <td colspan="2" class="text-right" style="color: #00cc66; font-size: 18px;">RM <?php echo number_format($netPay, 2); ?></td>
            </tr>
        </table>

        <div class="footer-sign">
            <div class="sign-box" style="border: none;"></div>
            <div class="sign-box">Approved By Management</div>
        </div>

        <div class="footer">
            DRILLTECH HDD Sdn. Bhd. &copy; All Rights Reserved. <br>
            <span style="font-size: 9px; color: #cbd5e1;">This is a system generated document and requires no physical signature.</span>
        </div>
    </div>
</div>

</body>
</html>