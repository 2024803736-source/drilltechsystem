<?php
include("database.php"); // sambung DB

$result = mysqli_query($conn, "SELECT * FROM admin ");
?>
<!DOCTYPE html>
<html>
<head>
  <title>DrillTech System</title>
</head>
<body>
  <h1>Admin List</h1>
  <ul>
    <?php while($row = mysqli_fetch_assoc($result)) { ?>
      <li><?php echo $row['Admin_Name']; ?> - <?php echo $row['Admin_Email']; ?></li>
    <?php } ?>
  </ul>
</body>
</html>
   <title>Admin Dashboard</title>
    <style>
        body {
            background-image: url('construction_bg.jpg');
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