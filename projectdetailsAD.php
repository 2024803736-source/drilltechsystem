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

    $engineer = $_POST['engineer'];
    $supervisor = $_POST['supervisor'];
    $workers = isset($_POST['workers']) ? $_POST['workers'] : [];

    $employees = [$engineer, $supervisor];
    foreach($employees as $emp){
        if(!empty($emp)){
            mysqli_query($conn, "INSERT INTO assigned_employee (Project_ID, Employee_ID) VALUES ('$projectID','$emp')");
        }
    }
    foreach($workers as $w){
        mysqli_query($conn, "INSERT INTO assigned_employee (Project_ID, Employee_ID) VALUES ('$projectID','$w')");
    }

    if(isset($_POST['duration'])){
        foreach($_POST['duration'] as $eqID => $dur){
            if(!empty($dur)){
                mysqli_query($conn, "INSERT INTO equipment_usage (Project_ID, Equipment_ID, Equipment_Duration) VALUES ('$projectID','$eqID','$dur')");
            }
        }
    }

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
        * { margin: 0; padding: 0; box-sizing: border-box; }
         body {
            font-family: Arial, sans-serif;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.65)), 
                        url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: black;
            min-height: 100vh;
            margin: 0;
        }

        /* ===== HEADER ===== */
        .header {
            background: #4a4a4a;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 60px;
            z-index: 1000;
        }
        .logo {
            display: flex;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
            color: white;
        }
        .header-welcome {
            color: white;
            font-size: 15px;
        }

        /* ===== LAYOUT ===== */
        .wrapper {
            display: flex;
            margin-top: 60px;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 240px;
            background: #5a5a5a;
            min-height: calc(100vh - 60px);
            flex-shrink: 0;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #7a7a7a;
        }

        /* ===== CONTENT ===== */
        .content {
            flex: 1;
            padding: 30px;
        }

        /* ===== BOX ===== */
        .box {
            background: #dcdcdc;
            border: 1px solid #bbb;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
        }
        .box h2 {
            margin-bottom: 15px;
            border-bottom: 2px solid #888;
            padding-bottom: 8px;
        }
        .box p {
            margin-bottom: 8px;
            font-size: 15px;
        }

        /* ===== FORM ===== */
        .form-box {
            background: #dcdcdc;
            border: 1px solid #bbb;
            border-radius: 10px;
            padding: 25px;
        }
        .form-box h3 {
            margin: 20px 0 10px;
            color: #333;
            border-bottom: 1px solid #aaa;
            padding-bottom: 5px;
        }
        .form-box label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .form-box select {
            padding: 8px 12px;
            border-radius: 5px;
            border: 1px solid #bbb;
            background: #f0f0f0;
            font-size: 14px;
            width: 100%;
            max-width: 350px;
            margin-bottom: 15px;
        }
        .form-box input[type="checkbox"] {
            margin-right: 8px;
        }
        .checkbox-item {
            margin-bottom: 6px;
        }
        .form-box button {
            margin-top: 20px;
            padding: 10px 25px;
            background: #5a5a5a;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 15px;
            cursor: pointer;
        }
        .form-box button:hover {
            background: #3a3a3a;
        }

        /* ===== TABLE ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: #f0f0f0;
        }
        th, td {
            border: 1px solid #bbb;
            padding: 10px;
            text-align: center;
        }
        th {
            background: #5a5a5a;
            color: white;
        }
        tr:hover { background: #e0e0e0; }

        .form-box input[type="text"] {
            padding: 6px 10px;
            border-radius: 5px;
            border: 1px solid #bbb;
            background: #f0f0f0;
            font-size: 14px;
            width: 150px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="logo">
            <span style="font-size:32px; margin-right:10px;">🔧</span>
            DRILLTECH
        </div>
        <div class="header-welcome">Welcome, <?php echo htmlspecialchars($admin_name); ?></div>
    </div>

    <!-- Wrapper -->
    <div class="wrapper">

        <!-- Sidebar -->
        <div class="sidebar">
            <a href="admin.php">📊 DASHBOARD</a>
            <a href="projectAD.php" class="active">📁 PROJECT</a>
            <a href="employeeAD.php">👷 EMPLOYEE</a>
        </div>

        <!-- Content -->
        <div class="content">

            <!-- Project Info -->
            <div class="box">
                <h2>Project Detail</h2>
                <p><b>PROJECT ID:</b> <?php echo htmlspecialchars($project['Project_ID']); ?></p>
                <p><b>PROJECT NAME:</b> <?php echo htmlspecialchars($project['Project_Name']); ?></p>
                <p><b>CLIENT:</b> <?php echo htmlspecialchars($project['Client_ID']); ?></p>
            </div>

            <!-- Form -->
            <div class="form-box">
                <form method="post">

                    <h3>Assign Employee by Position</h3>

                    <label>Site Engineer:</label>
                    <select name="engineer">
                        <?php while($row = mysqli_fetch_assoc($engineers)){ ?>
                            <option value="<?php echo $row['Employee_ID']; ?>">
                                <?php echo $row['Employee_ID']." - ".$row['Employee_Name']; ?>
                            </option>
                        <?php } ?>
                    </select>

                    <label>Site Supervisor:</label>
                    <select name="supervisor">
                        <?php while($row = mysqli_fetch_assoc($supervisors)){ ?>
                            <option value="<?php echo $row['Employee_ID']; ?>">
                                <?php echo $row['Employee_ID']." - ".$row['Employee_Name']; ?>
                            </option>
                        <?php } ?>
                    </select>

                    <label>General Worker (pilih max 5):</label>
                    <?php while($row = mysqli_fetch_assoc($workers)){ ?>
                        <div class="checkbox-item">
                            <input type="checkbox" name="workers[]" value="<?php echo $row['Employee_ID']; ?>">
                            <?php echo $row['Employee_ID']." - ".$row['Employee_Name']; ?>
                        </div>
                    <?php } ?>

                    <h3>Equipment Assignment</h3>
                    <table>
                        <tr>
                            <th>Equipment ID</th>
                            <th>Equipment Name</th>
                            <th>Duration / Unit</th>
                        </tr>
                        <?php while($eq = mysqli_fetch_assoc($equipments)){ ?>
                        <tr>
                            <td><?php echo htmlspecialchars($eq['Equipment_ID']); ?></td>
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
                    </table>

                    <input type="hidden" name="project_id" value="<?php echo htmlspecialchars($project['Project_ID']); ?>">
                    <button type="submit">Save Project Detail</button>

                </form>
            </div>

        </div>

    </div>

</body>
</html>