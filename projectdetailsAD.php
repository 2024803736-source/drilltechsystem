<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: loginAD.php");
    exit();
}
include("database.php");

// Kalau form disubmit
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $projectID = $_POST['project_id'];

    // Simpan assignment employee
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

    // Simpan equipment usage
    if(isset($_POST['duration'])){
        foreach($_POST['duration'] as $eqID => $dur){
            if(!empty($dur)){
                mysqli_query($conn, "INSERT INTO equipment_usage (Project_ID, Equipment_ID, Equipment_Duration) VALUES ('$projectID','$eqID','$dur')");
            }
        }
    }

    // Update status projek → On Going
    mysqli_query($conn, "UPDATE project SET Project_Status='On Going' WHERE Project_ID='$projectID'");

    // Redirect balik ke projectAD.php
    header("Location: projectAD.php");
    exit();
}

// Ambil project detail ikut ID
$projectID = $_GET['id'];
$query = "SELECT * FROM project WHERE Project_ID='$projectID'";
$result = mysqli_query($conn, $query);
$project = mysqli_fetch_assoc($result);

// Ambil senarai employee ikut position
$engineers = mysqli_query($conn, "SELECT Employee_ID, Employee_Name FROM employee WHERE Employee_Position='Site Engineer'");
$supervisors = mysqli_query($conn, "SELECT Employee_ID, Employee_Name FROM employee WHERE Employee_Position='Site Supervisor'");
$workers = mysqli_query($conn, "SELECT Employee_ID, Employee_Name FROM employee WHERE Employee_Position='General Worker'");

// Ambil semua equipment
$equipments = mysqli_query($conn, "SELECT * FROM equipment");
?>
<!DOCTYPE html>
<html>
<head>
<title>Project Detail</title>
<style>
body {margin:0; font-family:Arial; background:#ffffff;}
.header {background:#e0e0e0; padding:15px; font-size:20px; color:#000; border-bottom:1px solid #ccc;}
.sidebar {width:200px; background:#d6d6d6; height:100vh; position:fixed; top:0; left:0; padding-top:30px; border-right:1px solid #bbb;}
.sidebar a {display:block; color:#000; padding:12px; text-decoration:none;}
.sidebar a:hover {background:#c0c0c0;}
.content {margin-left:220px; padding:20px;}
form {background:#f9f9f9; padding:20px; border:1px solid #ccc; border-radius:8px;}
label {font-weight:bold;}
table {width:100%; border-collapse:collapse; margin-top:10px;}
th, td {border:1px solid #ccc; padding:8px; text-align:center;}
th {background:#d6d6d6;}
</style>
</head>
<body>
<div class="header">Welcome, <?php echo $_SESSION['admin_name']; ?></div>
<div class="sidebar">
  <a href="admin.php">Dashboard</a>
  <a href="projectAD.php" style="background:#c0c0c0;">Project</a>
  <a href="employeeAD.php">Employee</a>
</div>
<div class="content">
  <h2>Project Detail</h2>
  <p><b>PROJECT ID:</b> <?php echo $project['Project_ID']; ?></p>
  <p><b>PROJECT NAME:</b> <?php echo $project['Project_Name']; ?></p>
  <p><b>CLIENT:</b> <?php echo $project['Client_ID']; ?></p>

  <form method="post">
    <h3>Assign Employee by Position</h3>

    <label>Site Engineer:</label><br>
    <select name="engineer">
      <?php while($row=mysqli_fetch_assoc($engineers)){ ?>
        <option value="<?php echo $row['Employee_ID']; ?>">
          <?php echo $row['Employee_ID']." - ".$row['Employee_Name']; ?>
        </option>
      <?php } ?>
    </select><br><br>

    <label>Site Supervisor:</label><br>
    <select name="supervisor">
      <?php while($row=mysqli_fetch_assoc($supervisors)){ ?>
        <option value="<?php echo $row['Employee_ID']; ?>">
          <?php echo $row['Employee_ID']." - ".$row['Employee_Name']; ?>
        </option>
      <?php } ?>
    </select><br><br>

    <label>General Worker (pilih max 5):</label><br>
    <?php while($row=mysqli_fetch_assoc($workers)){ ?>
      <input type="checkbox" name="workers[]" value="<?php echo $row['Employee_ID']; ?>">
      <?php echo $row['Employee_ID']." - ".$row['Employee_Name']; ?><br>
    <?php } ?>
    <br>

    <h3>Equipment Assignment</h3>
    <table>
      <tr>
        <th>Equipment ID</th>
        <th>Equipment Name</th>
        <th>Duration / Unit</th>
      </tr>
      <?php while($eq=mysqli_fetch_assoc($equipments)){ ?>
      <tr>
        <td><?php echo $eq['Equipment_ID']; ?></td>
        <td><?php echo $eq['Equipment_Name']; ?></td>
        <td>
          <?php if(strtolower($eq['Equipment_Name']) == "pipeline"){ ?>
            <input type="text" name="duration[<?php echo $eq['Equipment_ID']; ?>]" placeholder="e.g. 80 unit">
          <?php } else { ?>
            <input type="text" name="duration[<?php echo $eq['Equipment_ID']; ?>]" placeholder="e.g. 30 days">
          <?php } ?>
        </td>
      </tr>
      <?php } ?>
    </table><br>

    <input type="hidden" name="project_id" value="<?php echo $project['Project_ID']; ?>">
    <button type="submit">Save Project Detail</button>
  </form>
</div>
</body>
</html>
