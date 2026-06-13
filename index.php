<?php
<<<<<<< HEAD
session_start();
include("database.php"); // sambung DB

// Check if admin is authenticated (recommended practice)
if(!isset($_SESSION['admin_id'])){
=======
// 1. Initialize session handler at the absolute top of the file
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("database.php"); // Connect to your XAMPP MySQL Database

// Security Guard: Kick unauthenticated traffic back to the login portal
if (!isset($_SESSION['admin_id'])) {
>>>>>>> d4eda43a7adf8d34bdf8b8f7c62f083efc81c73a
    header("Location: loginAD.php");
    exit();
}

<<<<<<< HEAD
$adminName = $_SESSION['admin_name'];

// Query admin list
$result = mysqli_query($conn, "SELECT * FROM admin");
=======
// Extract identity token safely
$admin_name = $_SESSION['admin_name'] ?? 'Admin User';

// 2. Fetch live data sets from your system tables
// FIX: Fetch project data ONCE and store it in an array to prevent dual-cursor data drops
$project_query = mysqli_query($conn, "SELECT * FROM project");
$all_projects = [];
if ($project_query) {
    while ($row = mysqli_fetch_assoc($project_query)) {
        $all_projects[] = $row;
    }
}

$employee_records   = mysqli_query($conn, "SELECT * FROM employee");
$payroll_records    = mysqli_query($conn, "SELECT * FROM payroll");
>>>>>>> d4eda43a7adf8d34bdf8b8f7c62f083efc81c73a
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
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
=======
    <title>DrillTech Admin System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #1a1c1e;
            --sidebar-bg: #22252a;
            --card-bg: #2d3035;
            --input-bg: #202225;
            --accent-color: #ff9f1c; /* High-visibility industrial amber */
            --text-main: #ffffff;
            --text-muted: #9aa0a6;
            --border-color: #40444b;
            
            /* Status Track Fills */
            --status-ongoing: #4392f1;
            --status-ongoing-bg: rgba(67, 146, 241, 0.15);
            --status-completed: #4caf50;
            --status-completed-bg: rgba(76, 175, 80, 0.15);
            --status-pending: #ff9f1c;
            --status-pending-bg: rgba(255, 159, 28, 0.15);
        }

        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
        }

        /* ===== HEADER ===== */
        .header {
            background: var(--sidebar-bg);
            padding: 0 30px;
>>>>>>> d4eda43a7adf8d34bdf8b8f7c62f083efc81c73a
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
<<<<<<< HEAD
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
=======
            top: 0; left: 0; right: 0;
            height: 70px;
            z-index: 1000;
            border-bottom: 1px solid var(--border-color);
        }
        
        .logo {
            display: flex;
            align-items: center;
            font-size: 20px;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: 1px;
        }
        
        .logo svg {
            width: 24px;
            height: 24px;
            margin-right: 12px;
            stroke: var(--accent-color);
        }
        
        .header-welcome {
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 500;
        }
        
        .header-welcome span {
            color: var(--text-main);
            font-weight: 600;
        }

        .logout-link {
            color: #ff4d4d;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            margin-left: 20px;
            border: 1px solid rgba(255, 77, 77, 0.2);
            padding: 6px 12px;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .logout-link:hover {
            background: rgba(255, 77, 77, 0.1);
        }

        /* ===== LAYOUT ====== */
        .wrapper {
            display: flex;
            padding-top: 70px;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            min-height: calc(100vh - 70px);
            position: fixed;
            top: 70px; bottom: 0; left: 0;
            padding-top: 20px;
            border-right: 1px solid var(--border-color);
            z-index: 999;
>>>>>>> d4eda43a7adf8d34bdf8b8f7c62f083efc81c73a
        }
        
        .sidebar a {
            display: flex;
            align-items: center;
<<<<<<< HEAD
            padding: 14px 25px;
            color: rgba(255, 255, 255, 0.85);
=======
            padding: 14px 24px;
            color: var(--text-muted);
>>>>>>> d4eda43a7adf8d34bdf8b8f7c62f083efc81c73a
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.2s ease;
<<<<<<< HEAD
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
=======
            margin: 4px 12px;
            border-radius: 8px;
            cursor: pointer;
        }
        
        .sidebar a:hover {
            color: var(--text-main);
            background: rgba(255, 255, 255, 0.05);
        }
        
        .sidebar a.active {
            background: var(--accent-color);
            color: #000000;
        }

        /* ===== MAIN CONTENT CONTAINER ===== */
        .main {
            flex: 1;
            margin-left: 260px;
            padding: 40px;
            max-width: 1400px;
            width: calc(100% - 260px);
        }

        /* ===== CARD ARCHITECTURE ===== */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        .card h2 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 24px;
            color: var(--text-main);
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 12px;
        }

        .project-meta {
            background: var(--input-bg);
            padding: 14px 20px;
            border-radius: 8px;
            border-left: 4px solid var(--accent-color);
            margin-bottom: 25px;
            font-size: 14px;
            color: var(--text-muted);
        }

        .project-meta strong {
            color: var(--text-main);
        }

        /* ===== MODULAR FORM CONTROLS ===== */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .form-group label {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .form-group select, .form-group input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: var(--input-bg);
            color: var(--text-main);
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-group select:focus, .form-group input:focus {
            border-color: var(--accent-color);
        }

        /* ===== TABLES ===== */
        .table-container {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            text-align: left;
        }
        
        th, td {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
            font-size: 14px;
        }
        
        th {
            background: #25282d;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        
        tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover { 
            background: rgba(255, 255, 255, 0.01); 
        }

        table input[type="text"] {
            width: 100%;
            padding: 6px 10px;
            background: var(--input-bg);
            border: 1px solid var(--accent-color);
            border-radius: 4px;
            color: #fff;
            font-family: inherit;
            font-size: 14px;
        }

        /* ===== STATUS PILLS ===== */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }
        
        .status-ongoing { color: var(--status-ongoing); background: var(--status-ongoing-bg); }
        .status-completed { color: var(--status-completed); background: var(--status-completed-bg); }
        .status-pending { color: var(--status-pending); background: var(--status-pending-bg); }

        /* ===== ACTIONS HUB ===== */
        .actions {
            margin-top: 25px;
            display: flex;
            gap: 12px;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s ease;
        }
        
        .save-btn { background: var(--accent-color); color: #000; }
        .edit-btn { background: transparent; color: var(--text-main); border: 1px solid var(--border-color); }
        .btn:hover { opacity: 0.85; }

        .hidden { display: none !important; }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
            </svg>
            DRILLTECH
        </div>
        <div class="header-welcome">
            Logged in as: <span><?php echo htmlspecialchars($admin_name); ?></span>
            <a href="logout.php" class="logout-link">Sign Out</a>
        </div>
    </div>

    <div class="wrapper">

        <div class="sidebar">
            <a id="nav-dashboard" onclick="showSection('dashboard')" class="active">📊 Dashboard</a>
            <a id="nav-project" onclick="showSection('project')">📁 Project</a>
            <a id="nav-equipment" onclick="showSection('equipment')">⚙️ Equipment</a>
            <a id="nav-employee" onclick="showSection('employee')">👷 Employee</a>
            <a id="nav-payroll" onclick="showSection('payroll')">💳 Payroll</a>
            <a id="nav-report" onclick="showSection('report')">📈 Report</a>
        </div>

        <div class="main">

            <div id="dashboard" class="card">
                <h2>Dashboard Overview</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Project ID</th>
                                <th>Project Name</th>
                                <th>Client ID</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($all_projects as $proj): 
                                $status_class = "status-pending";
                                $check_status = strtolower($proj['status'] ?? $proj['Status'] ?? '');
                                if($check_status == 'on going' || $check_status == 'ongoing') $status_class = "status-ongoing";
                                if($check_status == 'completed') $status_class = "status-completed";
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($proj['project_ID'] ?? $proj['Project_ID'] ?? ''); ?></td>
                                <td style="font-weight:500;"><?php echo htmlspecialchars($proj['project_name'] ?? $proj['Project_Name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($proj['client_ID'] ?? $proj['Client_ID'] ?? 'N/A'); ?></td>
                                <td><span class="status-badge <?php echo $status_class; ?>"><?php echo htmlspecialchars($proj['status'] ?? $proj['Status'] ?? 'Pending'); ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="project" class="card hidden">
                <h2>Project Assignment</h2>
                <div class="project-meta">
                    <strong>Focus Operations Target:</strong> Active Project Management Framework
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Site Engineer</label>
                        <select><option>E-001 Hun Hakimi</option><option>E-202 Abdul Mateen</option></select>
                    </div>
                    <div class="form-group">
                        <label>Site Supervisor</label>
                        <select><option>E-456 Thaqif Adzman</option><option>E-030 Khairul Danish</option></select>
                    </div>
                    <div class="form-group">
                        <label>General Worker</label>
                        <select><option>E-326 Muhd Uzair</option><option>E-103 Daniel Hakim</option></select>
                    </div>
                </div>
                <button class="btn save-btn">Save Project Detail</button>
            </div>

            <div id="equipment" class="card hidden">
                <h2>Project Equipment</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Machine Type</label>
                        <select><option>M-001 Goodong</option><option>M-002 Backhoe</option><option>M-003 Crane</option></select>
                    </div>
                    <div class="form-group">
                        <label>Pipe Quantity</label>
                        <input type="number" placeholder="Enter Quantity of Pipe">
                    </div>
                    <div class="form-group">
                        <label>Duration Limit</label>
                        <input type="date">
                    </div>
                </div>
                <button class="btn save-btn">Save Equipment Record</button>
            </div>

            <div id="employee" class="card hidden">
                <h2>Employee Management</h2>
                <div class="table-container">
                    <table id="employeeTable">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($emp = mysqli_fetch_assoc($employee_records)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($emp['employee_ID'] ?? $emp['Employee_ID'] ?? ''); ?></td>
                                <td style="font-weight:500;"><?php echo htmlspecialchars($emp['name'] ?? $emp['Name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($emp['position'] ?? $emp['Position'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($emp['contact'] ?? $emp['Contact'] ?? ''); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="actions">
                    <button class="btn save-btn" onclick="saveChanges('employeeTable', 'employee')">Save Configuration</button>
                    <button class="btn edit-btn" onclick="enableEditing('employeeTable', 'employee')">Edit Details</button>
                </div>
            </div>

            <div id="payroll" class="card hidden">
                <h2>Payroll Management</h2>
                <div class="table-container">
                    <table id="payrollTable">
                        <thead>
                            <tr>
                                <th>Payroll ID</th>
                                <th>Employee ID</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($pay = mysqli_fetch_assoc($payroll_records)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pay['payroll_ID'] ?? $pay['Payroll_ID'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($pay['employee_ID'] ?? $pay['Employee_ID'] ?? ''); ?></td>
                                <td style="font-weight:600; color:var(--status-completed);">RM <?php echo htmlspecialchars($pay['amount'] ?? $pay['Amount'] ?? '0.00'); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="actions">
                    <button class="btn save-btn" onclick="saveChanges('payrollTable', 'payroll')">Save Payroll</button>
                    <button class="btn edit-btn" onclick="enableEditing('payrollTable', 'payroll')">Edit Invoices</button>
                </div>
            </div>

            <div id="report" class="card hidden">
                <h2>Project Reporting</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Project ID</th>
                                <th>Project Name</th>
                                <th>Current Execution Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($all_projects as $rep): 
                                $rep_status = "status-pending";
                                $check_rep_status = strtolower($rep['status'] ?? $rep['Status'] ?? '');
                                if($check_rep_status == 'on going' || $check_rep_status == 'ongoing') $rep_status = "status-ongoing";
                                if($check_rep_status == 'completed') $rep_status = "status-completed";
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($rep['project_ID'] ?? $rep['Project_ID'] ?? ''); ?></td>
                                <td style="font-weight: 500;"><?php echo htmlspecialchars($rep['project_name'] ?? $rep['Project_Name'] ?? ''); ?></td>
                                <td><span class="status-badge <?php echo $rep_status; ?>"><?php echo htmlspecialchars($rep['status'] ?? $rep['Status'] ?? 'Pending'); ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        const editStates = { employee: false, payroll: false };

        function showSection(sectionId) {
            const sections = document.querySelectorAll('.main > .card');
            sections.forEach(sec => sec.classList.add('hidden'));
            document.getElementById(sectionId).classList.remove('hidden');

            const links = document.querySelectorAll('.sidebar a');
            links.forEach(link => link.classList.remove('active'));
            document.getElementById(`nav-${sectionId}`).classList.add('active');
        }

        function enableEditing(tableId, stateKey) {
            if (editStates[stateKey]) return;
            editStates[stateKey] = true;

            const table = document.getElementById(tableId);
            const rows = table.querySelectorAll("tbody tr");

            rows.forEach(row => {
                const cells = row.querySelectorAll("td");
                cells.forEach(cell => {
                    const value = cell.innerText;
                    cell.innerHTML = `<input type="text" value="${value}">`;
                });
            });
        }

        function saveChanges(tableId, stateKey) {
            if (!editStates[stateKey]) return;
            editStates[stateKey] = false;

            const table = document.getElementById(tableId);
            const rows = table.querySelectorAll("tbody tr");

            rows.forEach(row => {
                const cells = row.querySelectorAll("td");
                cells.forEach(cell => {
                    const input = cell.querySelector("input");
                    if (input) {
                        cell.innerText = input.value;
                    }
                });
            });
        }
    </script>
>>>>>>> d4eda43a7adf8d34bdf8b8f7c62f083efc81c73a
</body>
</html>