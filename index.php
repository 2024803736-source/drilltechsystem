<?php
include("database.php"); // sambung DB

$result = mysqli_query($conn, "SELECT * FROM CLIENT");
?>
<!DOCTYPE html>
<html>
<head>
  <title>DrillTech System</title>
</head>
<body>
  <h1>Client List</h1>
  <ul>
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
      <li><?php echo $row['Client_Name']; ?> - <?php echo $row['Client_Email']; ?></li>
    <?php } ?>
  </ul>
</body>
</html>
