<?php
include("database.php"); // sambung database

// Ambil semua data employee
$result = mysqli_query($conn, "SELECT * FROM employee");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee Page</title>
</head>
<body>
    <h1>Employee List</h1>
    <ul>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <li><?php echo $row['Employee_Name']; ?></li>
        <?php } ?>
    </ul>
</body>
</html>
