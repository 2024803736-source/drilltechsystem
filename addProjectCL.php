<?php
session_start();
include("database.php");

if(!isset($_SESSION['client_id'])){
    header("Location: loginCL.php");
    exit();
}

$client_id = $_SESSION['client_id'];
$client_name = $_SESSION['client_name'];

$success = "";
$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $project_name = mysqli_real_escape_string($conn, $_POST['project_name']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $value = floatval($_POST['value']);

    $sql = "INSERT INTO project (Project_Name, Project_Location, Project_Value, Project_Status, Client_ID) 
            VALUES ('$project_name', '$location', $value, 'Pending', $client_id)";

    if(mysqli_query($conn, $sql)){
        $success = "Project request submitted successfully! Waiting for admin approval.";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request New Project</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.65)), url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: white;
            min-height: 100vh;
        }
        .header {
            background: #003087;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .content {
            max-width: 620px;
            margin: 40px auto;
            background: rgba(0,0,0,0.85);
            padding: 40px;
            border-radius: 12px;
        }
        h2 { text-align: center; margin-bottom: 20px; }
        .success { color: #28a745; text-align: center; font-size: 18px; margin: 15px 0; }
        .error { color: #ff6b6b; text-align: center; margin: 15px 0; }
        label { display: block; margin: 15px 0 8px; font-weight: bold; }
        input { width: 100%; padding: 12px; border-radius: 8px; border: none; font-size: 16px; }
        .btn {
            width: 100%;
            padding: 15px;
            background: #ff8c00;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 18px;
            font-weight: bold;
            margin-top: 25px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🔧 DRILLTECH</div>
        <div>Welcome, <?php echo htmlspecialchars($client_name); ?></div>
    </div>

    <div class="content">
        <h2>Request New Project</h2>

        <?php if($success) echo "<p class='success'>$success</p>"; ?>
        <?php if($error) echo "<p class='error'>$error</p>"; ?>

        <form method="POST">
            <label>Project Name</label>
            <input type="text" name="project_name" required>

            <label>Location</label>
            <input type="text" name="location" required>

            <label>Project Value (RM)</label>
            <input type="number" name="value" step="0.01" required>

            <button type="submit" class="btn">Submit Project Request</button>
        </form>

        <p style="text-align:center; margin-top:25px;">
            <a href="projectCL.php" style="color:#1E90FF;">← Back to My Projects</a>
        </p>
    </div>
</body>
</html>