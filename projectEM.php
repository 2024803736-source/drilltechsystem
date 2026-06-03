<?php
session_start();
if(!isset($_SESSION['username'])){
    header("Location: loginEM.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Project Dashboard</title>
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
            <h2>Project Dashboard</h2>
            <table>
                <tr>
                    <th>Project ID</th>
                    <th>Project Name</th>
                    <th>Status</th>
                    <th>Due Date</th>
                </tr>
                <!-- baris kosong -->
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
