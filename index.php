<?php
session_start();
include("database.php"); // sambung DB

// Check if admin is authenticated (recommended practice)
if(!isset($_SESSION['admin_id'])){
    header("Location: loginAD.php");
    exit();
}

$adminName = $_SESSION['admin_name'];

// Query admin list
$result = mysqli_query($conn, "SELECT * FROM admin");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DrillTech HDD</title>
    <style>
        /* CSS Reset & Modern Typography */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            /* Lighter grey-tinted construction background overlay matching client/employee */
            background: linear-gradient(rgba(130, 124, 124, 0.45), rgba(15, 15, 15, 0.95)), 
                        url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: #f8fafc;
            min-height: 100vh;
        }

        /* Top Header Navigation (#827c7c grey) */
        .header {
            background: #827c7c;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            padding: 0 30px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        
        .logo { 
            font-size: 20px; 
            font-weight: 800; 
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 8px;
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

        /* Side Navigation Panel (#827c7c grey) */
        .sidebar {
            width: 240px;
            background: #827c7c;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            position: fixed;
            top: 64px;
            left: 0;
            height: calc(100vh - 64px);
            padding-top: 20px;
            z-index: 90;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.15);
        }
        
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 14px 25px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.2s ease;
        }
        
        /* Sidebar Hover and Active (using consistent corporate accent #ff8c00) */
        .sidebar a:hover { 
            color: #fff;
            background: #ff8c00;
        }

        .sidebar a.active { 
            color: #fff;
            background: #ff8c00; 
        }

        /* Main Dashboard Content Layout */
        .content { 
            margin-left: 260px; 
            padding: 94px 30px 40px 30px; 
        }

        /* Card Container (using grey transparent theme) */
        .card {
            background-color: rgba(60, 60, 60, 0.7); 
            border: 1px solid rgba(130, 124, 124, 0.3);
            border-radius: 12px; 
            padding: 28px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
            margin-bottom: 25px;
        }

        .card h2 {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
            border-bottom: 2px solid #ff8c00;
            padding-bottom: 10px;
        }

        /* Styled Table for Admin List */
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

        tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }
        
        th { 
            background: rgba(130, 124, 124, 0.4); 
            color: #ffcc00; 
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Logout Button */
        .logout-btn {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 6px 14px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: #ef4444;
            color: white;
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.35);
        }
    </style>
</head>
<body>
    <!-- Top Header Navigation -->
    <div class="header">
        <div class="logo">
            <span style="font-size: 24px; margin-right: 4px;">🔧</span> DRILLTECH
        </div>
        <div style="display: flex; align-items: center; gap: 15px;">
            <div class="user-welcome">Welcome, <?php echo htmlspecialchars($adminName); ?></div>
            <a href="logoutAD.php" class="logout-btn">Log Out</a>
        </div>
    </div>

    <!-- Side Navigation Bar -->
    <div class="sidebar">
        <a href="admin.php" class="active">📊 DASHBOARD</a>
        <a href="manageEmployees.php">👷 EMPLOYEES</a>
        <a href="manageClients.php">👥 CLIENTS</a>
        <a href="manageProjects.php">🔍 PROJECTS</a>
        <a href="managePayments.php">💰 PAYMENTS</a>
    </div>

    <!-- Main View Panel -->
    <div class="content">
        <!-- Dashboard Welcome Card -->
        <div class="card">
            <h2>Admin Dashboard Control Panel</h2>
            <p style="line-height: 1.6; color: #cbd5e1;">Welcome to the corporate administration workspace. You can oversee employee operations, client project submissions, payroll transactions, and database settings using the sidebar controls.</p>
        </div>

        <!-- Admin Members List Card -->
        <div class="card">
            <h2>Current Administrators</h2>
            <table>
                <thead>
                    <tr>
                        <th>Administrator Name</th>
                        <th>Registered Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['Admin_Name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['Admin_Email']); ?></td>
                    </tr>
                    <?php } ?>
                    
                    <?php if(mysqli_num_rows($result) == 0): ?>
                        <tr>
                            <td colspan="2" style="text-align: center; color: #64748b; font-style: italic; border: none;">
                                No administrator accounts found.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>