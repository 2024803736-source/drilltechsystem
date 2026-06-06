<?php
session_start();
if(!isset($_SESSION['admin_id'])){
    header("Location: loginAD.php");
    exit();
}
include("database.php");

// Ambil senarai projek
$query = "SELECT * FROM project ORDER BY Project_ID";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Project Dashboard</title>
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
.status-ongoing {color:blue; font-weight:bold;}
.status-completed {color:green; font-weight:bold;}
.status-pending {color:#555; font-weight:bold;}
</style>
</head>
<body>
<div class="header">Welcome, Admin</div>
<div class="sidebar">
  <a href="admin.php">Dashboard</a>
  <a href="projectAD.php" style="background:#c0c0c0;">Project</a>
  <a href="employeeAD.php">Employee</a>
</div>
<div class="content">
  <div class="box">
    <h2>Project List</h2>
    <table>
      <tr>
        <th>Project ID</th>
        <th>Project Name</th>
        <th>Client</th>
        <th>Status</th>
        <th>Location</th>
        <th>Value (RM)</th>
      </tr>
      <?php while($row = mysqli_fetch_assoc($result)){ 
          $statusClass = "";
          if($row['Project_Status'] === "On Going") $statusClass = "status-ongoing";
          elseif($row['Project_Status'] === "Completed") $statusClass = "status-completed";
          elseif($row['Project_Status'] === "Pending") $statusClass = "status-pending";
      ?>
      <tr>
        <td>
          <?php if($row['Project_Status'] === "Pending"){ ?>
            <a href="projectDetailAD.php?id=<?php echo $row['Project_ID']; ?>"><?php echo $row['Project_ID']; ?></a>
          <?php } else { ?>
            <?php echo $row['Project_ID']; ?>
          <?php } ?>
        </td>
        <td><?php echo $row['Project_Name']; ?></td>
        <td><?php echo $row['Client_ID']; ?></td>
        <td class="<?php echo $statusClass; ?>"><?php echo $row['Project_Status']; ?></td>
        <td><?php echo $row['Project_Location']; ?></td>
        <td><?php echo number_format($row['Project_Value'],2); ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>
  <div style="margin-top:20px; background:#fff; padding:15px; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
  <h2>Generate Project Report</h2>
  <form action="generateReportAD.php" method="get" target="_blank">
    <label for="projectID">Select Project:</label>
    <select name="id" id="projectID" required>
      <option value="">-- Choose Project ID --</option>
      <?php
      // Ambil senarai projek dari DB untuk dropdown
      $projList = mysqli_query($conn, "SELECT Project_ID, Project_Name FROM project ORDER BY Project_ID");
      while($p = mysqli_fetch_assoc($projList)){
          echo "<option value='".$p['Project_ID']."'>".$p['Project_ID']." - ".$p['Project_Name']."</option>";
      }
      ?>
    </select>
    <button type="submit">Generate Report</button>
  </form>
</div>
</div>

</body>
</html>
