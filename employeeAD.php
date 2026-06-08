<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: loginAD.php");
    exit();
}
include("database.php");

// Query join employee dengan payroll
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
<title>Employee Dashboard</title>
<style>
body {margin:0; font-family:Arial,sans-serif; background:#f4f4f4;}
.header {background:#e0e0e0; padding:15px; font-size:20px; color:#000; border-bottom:1px solid #ccc;}
.sidebar {width:220px; background:#d6d6d6; height:100vh; position:fixed; top:0; left:0; padding-top:30px; border-right:1px solid #bbb;}
.sidebar a {display:block; color:#000; padding:12px; text-decoration:none;}
.sidebar a:hover {background:#c0c0c0;}
.content {margin-left:220px; padding:20px;}
.box {background:#fff; border:1px solid #ccc; border-radius:8px; padding:20px; box-shadow:0 2px 4px rgba(0,0,0,0.1);}
table {width:100%; border-collapse:collapse; margin-top:10px;}
th, td {border:1px solid #ccc; padding:8px; text-align:center;}
th {background:#d6d6d6;}
.status-paid {color:green; font-weight:bold;}
.status-unpaid {color:red; font-weight:bold;}
</style>
</head>
<body>
<div class="header">Welcome, Admin</div>
<div class="sidebar">
  <a href="admin.php">Dashboard</a>
  <a href="projectAD.php">Project</a>
  <a href="employeeAD.php" style="background:#c0c0c0;">Employee</a>
</div>
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
      <?php while($row = mysqli_fetch_assoc($result)){ 
          $statusClass = ($row['Payroll_Status'] == "Paid") ? "status-paid" : "status-unpaid";
      ?>
      <tr>
        <td><?php echo $row['Employee_ID']; ?></td>
        <td><?php echo $row['Employee_Name']; ?></td>
        <td><?php echo $row['Employee_Position']; ?></td>
        <td><?php echo $row['Employee_Contact']; ?></td>
        <td><?php echo $row['Payroll_ID']; ?></td>
        <td><?php echo "RM".number_format($row['Payroll_Amount'],2); ?></td>
        <td class="<?php echo $statusClass; ?>"><?php echo $row['Payroll_Status']; ?></td>
        <td><?php echo $row['Payroll_Date']; ?></td>
        <td><?php echo $row['Payroll_Type']; ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>
</div>
</body>
</html>
