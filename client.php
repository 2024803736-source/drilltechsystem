<?php
include("database.php"); // sambung database

// Ambil semua data client
$result = mysqli_query($conn, "SELECT * FROM client");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Client Page</title>
</head>
<body>
    <h1>Client List</h1>
    <ul>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <li><?php echo $row['Client_Name']; ?></li>
        <?php } ?>
    </ul>
</body>
</html>
