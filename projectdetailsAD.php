<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
if(!isset($_SESSION['admin_id'])){
    header("Location: loginAD.php");
    exit();
}
include("database.php");

$admin_name = $_SESSION['admin_name'] ?? 'Admin';

// Kalau form disubmit
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $projectID = $_POST['project_id'];
    
    // Ambil data tarikh kalendar dari form
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    $engineer = $_POST['engineer'];
    $supervisor = $_POST['supervisor'];
    $workers = isset($_POST['workers']) ? $_POST['workers'] : [];

    // Server-side validation: Check if less than 5 workers are selected
    if(count($workers) < 5){
        echo "<script>alert('Please select at least 5 General Workers.'); window.history.back();</script>";
        exit();
    }

    // === LANGKAH KESELAMATAN: Padam rekod lama dulu untuk elak Duplicate Entry ===
    mysqli_query($conn, "DELETE FROM assigned_employee WHERE Project_ID = '$projectID'");
    mysqli_query($conn, "DELETE FROM equipment_usage WHERE Project_ID = '$projectID'");

    // Masukkan Engineer dan Supervisor baru sekali dengan tarikh projek mereka
    $employees = [$engineer, $supervisor];
    foreach($employees as $emp){
        if(!empty($emp)){
            mysqli_query($conn, "INSERT INTO assigned_employee (Project_ID, Employee_ID, ProjectEmp_StartD, ProjectEmp_EndD) VALUES ('$projectID', '$emp', '$startDate', '$endDate')");
        }
    }
    
    // Masukkan senarai General Workers baru sekali dengan tarikh projek mereka
    foreach($workers as $w){
        if(!empty($w)){
            mysqli_query($conn, "INSERT INTO assigned_employee (Project_ID, Employee_ID, ProjectEmp_StartD, ProjectEmp_EndD) VALUES ('$projectID', '$w', '$startDate', '$endDate')");
        }
    }

    if(isset($_POST['duration'])){
        foreach($_POST['duration'] as $eqID => $dur){
            if(!empty($dur)){
                mysqli_query($conn, "INSERT INTO equipment_usage (Project_ID, Equipment_ID, Equipment_Duration) VALUES ('$projectID','$eqID','$dur')");
            }
        }
    }

    // Ubah status project sahaja (Tanpa usik kolum tarikh di table project)
    mysqli_query($conn, "UPDATE project SET Project_Status='On Going' WHERE Project_ID='$projectID'");

    header("Location: projectAD.php");
    exit();
}

// Ambil project detail ikut ID
$projectID = $_GET['id'];
$query = "SELECT * FROM project WHERE Project_ID='$projectID'";
$result = mysqli_query($conn, $query);
$project = mysqli_fetch_assoc($result);

// Ambil senarai employee ikut position
$engineers   = mysqli_query($conn, "SELECT Employee_ID, Employee_Name FROM employee WHERE Employee_Position='Site Engineer'");
$supervisors = mysqli_query($conn, "SELECT Employee_ID, Employee_Name FROM employee WHERE Employee_Position='Site Supervisor'");
$workers     = mysqli_query($conn, "SELECT Employee_ID, Employee_Name FROM employee WHERE Employee_Position='General Worker'");

// Ambil semua equipment
$equipments = mysqli_query($conn, "SELECT * FROM equipment");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Detail - DrillTech Admin</title>
    <style>
        /* Reset & Base Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(rgba(74, 74, 74, 0.45), rgba(15, 15, 15, 0.95)), 
                        url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: #f8fafc;
            min-height: 100vh;
        }

        /* ===== HEADER (#4a4a4a grey) ===== */
        .header {
            background: #4a4a4a;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 60px;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        
        .logo {
            display: flex;
            align-items: center;
            font-size: 20px;
            font-weight: 800;
            color: white;
            letter-spacing: 1px;
        }
        
        .header-welcome {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 6px 14px;
            border-radius: 20px;
        }

        /* ===== LAYOUT WRAPPER ===== */
        .wrapper {
            display: flex;
            margin-top: 60px;
        }

        /* ===== SIDEBAR (#5a5a5a grey) ===== */
        .sidebar {
            width: 240px;
            background: #5a5a5a;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            min-height: calc(100vh - 60px);
            position: fixed;
            top: 60px;
            left: 0;
            z-index: 90;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.15);
        }
        
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 14px 25px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.2s ease;
        }
        
        .sidebar a:hover, .sidebar a.active {
            color: #fff;
            background: #7a7a7a;
        }

        /* ===== CONTENT PANEL ===== */
        .content {
            flex: 1;
            margin-left: 240px;
            padding: 35px 30px;
        }

        /* ===== BOX/CARDS ===== */
        .box, .form-box {
            background: rgba(45, 45, 45, 0.85); 
            border: 1px solid rgba(120, 120, 120, 0.25);
            border-radius: 12px;
            padding: 28px;
            margin-bottom: 25px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
        }

        .box h2, .form-box h3 {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
            border-bottom: 2px solid #ff8c00;
            padding-bottom: 10px;
            color: #fff;
        }

        .box p {
            margin-bottom: 10px;
            font-size: 15px;
            color: #cbd5e1;
        }

        /* ===== FORM FIELDS ===== */
        .form-box h3 {
            margin: 25px 0 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            padding-bottom: 8px;
        }
        
        .form-box label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #cbd5e1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .form-box select, .form-box input[type="date"] {
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: rgba(0, 0, 0, 0.3);
            color: white;
            font-size: 14px;
            width: 100%;
            max-width: 350px;
            margin-bottom: 20px;
            outline: none;
            transition: all 0.3s;
        }

        .form-box select:focus, .form-box input[type="date"]:focus {
            border-color: #ff8c00;
            box-shadow: 0 0 0 3px rgba(255, 140, 0, 0.25);
        }

        select option {
            background-color: #282828;
            color: white;
        }

        /* Container susunan kalendar sebelah-menyebelah */
        .date-range-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .date-field {
            flex: 1;
            min-width: 160px;
            max-width: 250px;
        }

        /* Checkboxes */
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 15px;
            color: #e2e8f0;
        }

        .checkbox-item input[type="checkbox"] {
            accent-color: #ff8c00;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        /* Input Text inside Table */
        .form-box input[type="text"] {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: rgba(0, 0, 0, 0.3);
            color: white;
            font-size: 14px;
            width: 100%;
            max-width: 180px;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-box input[type="text"]:focus {
            border-color: #888;
        }

        /* ===== TABLE ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
        }
        
        th, td {
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 14px;
        }
        
        th {
            background: rgba(130, 124, 124, 0.4);
            color: #ffcc00;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Save Button */
        .form-box button {
            margin-top: 20px;
            padding: 14px 32px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2);
        }

        .form-box button:hover {
            background: #218838;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo">
            <span style="font-size: 24px; margin-right: 8px;">🔧</span>
            DRILLTECH
        </div>
        <div class="header-welcome">Welcome, <?php echo htmlspecialchars($admin_name); ?></div>
    </div>

    <div class="wrapper">

        <div class="sidebar">
            <a href="admin.php">📊 DASHBOARD</a>
            <a href="projectAD.php" class="active">📁 PROJECT</a>
            <a href="employeeAD.php">👷 EMPLOYEE</a>
        </div>

        <div class="content">

            <div class="box">
                <h2>Project Detail</h2>
                <p><b>PROJECT ID:</b> #<?php echo htmlspecialchars($project['Project_ID']); ?></p>
                <p><b>PROJECT NAME:</b> <?php echo htmlspecialchars($project['Project_Name']); ?></p>
                <p><b>CLIENT ID:</b> #<?php echo htmlspecialchars($project['Client_ID']); ?></p>
            </div>

            <div class="form-box">
                <form method="post" onsubmit="return validateWorkers();">

                    <h3>Employee Assignment Timeline</h3>
                    <div class="date-range-container">
                        <div class="date-field">
                            <label for="start_date">Start Date:</label>
                            <input type="date" id="start_date" name="start_date" required>
                        </div>
                        <div class="date-field">
                            <label for="end_date">End Date:</label>
                            <input type="date" id="end_date" name="end_date" required>
                        </div>
                    </div>

                    <h3>Assign Employee by Position</h3>

                    <label>Site Engineer:</label>
                    <select name="engineer" required>
                        <option value="">-- Choose Engineer --</option>
                        <?php 
                        mysqli_data_seek($engineers, 0); // Reset pointer
                        while($row = mysqli_fetch_assoc($engineers)){ ?>
                            <option value="<?php echo $row['Employee_ID']; ?>">
                                <?php echo $row['Employee_ID']." - ".$row['Employee_Name']; ?>
                            </option>
                        <?php } ?>
                    </select>

                    <label>Site Supervisor:</label>
                    <select name="supervisor" required>
                        <option value="">-- Choose Supervisor --</option>
                        <?php 
                        mysqli_data_seek($supervisors, 0); // Reset pointer
                        while($row = mysqli_fetch_assoc($supervisors)){ ?>
                            <option value="<?php echo $row['Employee_ID']; ?>">
                                <?php echo $row['Employee_ID']." - ".$row['Employee_Name']; ?>
                            </option>
                        <?php } ?>
                    </select>

<<<<<<< Updated upstream
                    <label style="color: #ffcc00;">General Worker (Please select at least 5 workers):</label>
=======
                    <label>General Worker (choose max 5):</label>
>>>>>>> Stashed changes
                    <?php 
                    mysqli_data_seek($workers, 0); // Reset pointer
                    while($row = mysqli_fetch_assoc($workers)){ ?>
                        <div class="checkbox-item">
                            <input type="checkbox" name="workers[]" value="<?php echo $row['Employee_ID']; ?>" class="worker-checkbox">
                            <span><?php echo $row['Employee_ID']." - ".$row['Employee_Name']; ?></span>
                        </div>
                    <?php } ?>

                    <h3>Equipment Assignment</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Equipment ID</th>
                                <th>Equipment Name</th>
                                <th>Duration / Unit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            mysqli_data_seek($equipments, 0); // Reset pointer
                            while($eq = mysqli_fetch_assoc($equipments)){ ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($eq['Equipment_ID']); ?></td>
                                <td><?php echo htmlspecialchars($eq['Equipment_Name']); ?></td>
                                <td>
                                    <?php if(strtolower($eq['Equipment_Name']) == "pipeline"){ ?>
                                        <input type="text" name="duration[<?php echo $eq['Equipment_ID']; ?>]" placeholder="e.g. 80 unit">
                                    <?php } else { ?>
                                        <input type="text" name="duration[<?php echo $eq['Equipment_ID']; ?>]" placeholder="e.g. 30 days">
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($project['Project_ID']); ?>">
                    <button type="submit">Save Project Detail</button>

                </form>
            </div>

        </div>

    </div>

    <script>
    function validateWorkers() {
        var checkboxes = document.querySelectorAll('.worker-checkbox');
        var checkedCount = 0;
        
        // Count how many checkboxes are ticked
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                checkedCount++;
            }
        }
        
        // Validation: Must select at least 5 workers
        if (checkedCount < 5) {
            alert("⚠️ Warning: You must select at least 5 General Workers! Currently selected: " + checkedCount);
            return false; 
        }
        
        return true; 
    }
    </script>

</body>
</html>