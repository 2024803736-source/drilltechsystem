<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: loginEM.php");
    exit();
}
include("database.php");

$employeeID = $_SESSION['employee_id'];

// Ambil payroll untuk employee login
$result = mysqli_query($conn, "SELECT * FROM payroll WHERE Employee_ID='$employeeID'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payroll Dashboard</title>
    <style>
        body {
            background-image: url('construction_bg.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            color: white;
            margin: 0;
        }
        .header {
            background-color: #006400;
            padding: 15px;
            font-size: 20px;
            color: white;
        }
        .sidebar {
            width: 200px;
            background-color: #006400;
            height: 100vh;
            position: fixed;
            padding-top: 30px;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 12px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #228B22;
        }
        .content {
            margin-left: 220px;
            padding: 20px;
        }
        .table-container {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            margin: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            color: white;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }
        th {
            color: #FFD700;
        }
    </style>
</head>
<body>
    <div class="header">Welcome, <?php echo $_SESSION['username']; ?></div>

    <div class="sidebar">
        <a href="employee.php">Dashboard</a>
        <a href="projectEM.php">Project</a>
        <a href="profileEM.php">Profile</a>
        <a href="payrollEM.php">Payroll</a>
    </div>

    <div class="content">
        <div class="table-container">
            <h2>Payroll Management Dashboard</h2>
            <table>
                <tr>
                    <th>Payroll ID</th>
                    <th>Employee ID</th>
                    <th>Payroll Date</th>
                    <th>Payroll Amount</th>
                    <th>Payroll Status</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['Payroll_ID']; ?></td>
                    <td><?php echo $row['Employee_ID']; ?></td>
                    <td><?php echo $row['Payroll_Date']; ?></td>
                    <td><?php echo $row['Payroll_Amount']; ?></td>
                    <td><?php echo $row['Payroll_Status']; ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>
</html>
