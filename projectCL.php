<?php
session_start();
include("database.php");

if(!isset($_SESSION['client_id'])){
    header("Location: loginCL.php");
    exit();
}

$client_id = $_SESSION['client_id'];
$client_name = $_SESSION['client_name'];

// Fetch projects
$projects = mysqli_query($conn, "
    SELECT p.*, ae.ProjectEmp_EndD as Deadline 
    FROM project p 
    LEFT JOIN assigned_employee ae ON p.Project_ID = ae.Project_ID 
    WHERE p.Client_ID = $client_id 
    GROUP BY p.Project_ID 
    ORDER BY p.Project_ID DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - DrillTech HDD</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.65)), url('backgroundCSC264.png') center/cover no-repeat fixed;
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
        .status {
            padding: 6px 18px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
        }
        .status-ongoing { background: #28a745; color: white; }
        .status-completed { background: #007bff; color: white; }
        .status-pending { background: #ffc107; color: black; }

        /* Centered Add Project Button */
        .button-container {
            text-align: center;
            margin-top: 30px;
        }
        .add-project {
            background: linear-gradient(135deg, #007bff, #00aaff);
            color: white;
            padding: 14px 32px;
            border: none;
            border-radius: 50px;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .add-project:hover {
            background: linear-gradient(135deg, #0056b3, #0099ff);
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.5);
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
        <a href="projectCL.php" class="active">🔍 PROJECT</a>
        <a href="paymentCL.php">💰 PAYMENT</a>
    </div>

    <div class="content">
        <div class="main-box">
            <h2>Projects:</h2>
            
            <table>
                <tr>
                    <th>Project ID</th>
                    <th>Project Name</th>
                    <th>Project Status</th>
                    <th>Project Value</th>
                    <th>Deadline</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($projects)): ?>
                <tr>
                    <td><?php echo $row['Project_ID']; ?></td>
                    <td><?php echo htmlspecialchars($row['Project_Name']); ?></td>
                    <td>
                        <?php 
                        $status = $row['Project_Status'];
                        $class = ($status == 'On Going') ? 'status-ongoing' : 
                                (($status == 'Completed') ? 'status-completed' : 'status-pending');
                        echo "<span class='status $class'>$status</span>";
                        ?>
                    </td>
                    <td>RM <?php echo number_format($row['Project_Value'], 2); ?></td>
                    <td><?php echo $row['Deadline'] ?? 'N/A'; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>

            <!-- Centered Button -->
         <div class="button-container">
         <a href="addProjectCL.php">
        <button class="add-project">Add Project (+)</button>
         </a>
         </div>
        </div>
    </div>
</body>
</html>