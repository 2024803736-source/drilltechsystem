<?php
include("database.php"); // Connect DB

$result = mysqli_query($conn, "SELECT * FROM admin ");
// Set a fallback if session name isn't set yet
$admin_name = $_SESSION['admin_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
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
        }
        
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 14px 24px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.2s ease;
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
        
        .form-group h3, .form-group label {
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

        /* Table Inputs inside editing state */
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
        <div class="header-welcome">Logged in as: <span><?php echo htmlspecialchars($admin_name); ?></span></div>
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
                            <tr><th>Project ID</th><th>Project Name</th><th>Client</th><th>Status</th><th>Start Date</th><th>End Date</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>A01</td><td style="font-weight:500;">River Crossing</td><td>ABC Corp</td><td><span class="status-badge status-completed">Completed</span></td><td>01/04/2026</td><td>30/06/2026</td></tr>
                            <tr><td>A02</td><td style="font-weight:500;">Pipeline Delta</td><td>XYZ Holdings</td><td><span class="status-badge status-pending">Pending</span></td><td>15/05/2026</td><td>15/08/2026</td></tr>
                            <tr><td>A03</td><td style="font-weight:500;">Site Alpha</td><td>MegaBuild</td><td><span class="status-badge status-ongoing">On Going</span></td><td>01/01/2026</td><td>15/03/2026</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="project" class="card hidden">
                <h2>Project Assignment</h2>
                <div class="project-meta">
                    <strong>Project ID:</strong> A03 &nbsp;|&nbsp; <strong>Project Name:</strong> Site Alpha &nbsp;|&nbsp; <strong>Client:</strong> MegaBuild
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
                <div class="project-meta">
                    <strong>Project ID:</strong> A03 &nbsp;|&nbsp; <strong>Project Name:</strong> Site Alpha &nbsp;|&nbsp; <strong>Client:</strong> MegaBuild
                </div>
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
                            <tr><th>Employee ID</th><th>Name</th><th>Position</th><th>Contact</th><th>Assigned Project</th><th>Payroll</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>E-1001</td><td>Ahmad Zulkifli</td><td>Technician</td><td>012-3456789</td><td>Site Alpha</td><td>RM3500</td></tr>
                            <tr><td>E-1002</td><td>Nur Aisyah</td><td>Supervisor</td><td>013-9876543</td><td>Pipeline Delta</td><td>RM4800</td></tr>
                            <tr><td>E-1003</td><td>Lim Wei Han</td><td>Engineer</td><td>017-2233445</td><td>Hilltop Install</td><td>RM5000</td></tr>
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
                            <tr><th>Payroll ID</th><th>Employee ID</th><th>Amount</th><th>Status</th><th>Date</th><th>Type</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>PR-001</td><td>E-1001</td><td>RM 3,200</td><td>Paid</td><td>01/05/2026</td><td>Part Time</td></tr>
                            <tr><td>PR-005</td><td>E-1002</td><td>RM 4,500</td><td>Paid</td><td>01/05/2026</td><td>Full Time</td></tr>
                            <tr><td>PR-018</td><td>E-1003</td><td>RM 5,000</td><td>Paid</td><td>01/05/2026</td><td>Full Time</td></tr>
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
                            <tr><th>Project ID</th><th>Project Name</th><th>Status</th><th>Value</th><th>Deadline</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>A01</td><td style="font-weight: 500;">Site Alpha</td><td><span class="status-badge status-ongoing">On Going</span></td><td style="font-weight:600;">RM 800,000.00</td><td>15/06/2026</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Track unique edit states dynamically across panels
        const editStates = {
            employee: false,
            payroll: false
        };

        // Navigation Controller
        function showSection(sectionId) {
            // Drop hidden tokens on modules
            const sections = document.querySelectorAll('.main > .card');
            sections.forEach(sec => sec.classList.add('hidden'));

            // Wake targeted workspace
            document.getElementById(sectionId).classList.remove('hidden');

            // Handle matching active flags across sidebar links
            const links = document.querySelectorAll('.sidebar a');
            links.forEach(link => link.classList.remove('active'));
            document.getElementById(`nav-${sectionId}`).classList.add('active');
        }

        // Standardized Multi-Table Core Row Inline Matrix Editor
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

        // Standardized Table Matrix Data Sync Engine
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
</body>
</html>
   <title>Admin Dashboard</title>
    <style>
        body {
            background-image: url('images/construction_bg.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            color: white;
            margin: 0;
        }
        .header {
            background-color: #827c7cea;
            padding: 15px;
            font-size: 20px;
            color: white;
        }
        .sidebar {
            width: 200px;
            background-color: #827c7cea;
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
            background-color: #827c7cea;
        }
        .content {
            margin-left: 220px;
            padding: 20px;
        }
        .card {
            background-color: rgba(99, 105, 99, 0.8);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
