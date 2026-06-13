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
    <title>Request New Project - DrillTech HDD</title>
    <style>
        /* Reset & Layout Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            /* Blue-tinted construction background overlay */
            background: linear-gradient(rgba(0, 48, 135, 0.45), rgba(15, 20, 30, 0.9)), 
                        url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: #f8fafc;
            min-height: 100vh;
        }

        /* Top Header Navigation (#003087 blue) */
        .header {
            background: #003087;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            padding: 0 30px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        
        .logo { 
            font-size: 20px; 
            font-weight: 800; 
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-welcome {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 6px 14px;
            border-radius: 20px;
        }

        /* Form Container Box */
        .content {
            background: rgba(0, 0, 0, 0.65);
            border: 1px solid rgba(0, 48, 135, 0.35);
            padding: 35px 30px;
            border-radius: 12px;
            width: 100%;
            max-width: 480px;
            margin: 110px auto 40px; /* offset for fixed header */
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.45);
        }

        h2 { 
            font-size: 20px;
            font-weight: 700;
            color: #fff; 
            text-align: center; 
            margin-bottom: 25px; 
            border-bottom: 2px solid #ff8c00;
            padding-bottom: 10px;
            letter-spacing: 0.5px;
        }

        /* Messages */
        .success { 
            background: rgba(40, 167, 69, 0.12); 
            border: 1px solid rgba(40, 167, 69, 0.25);
            color: #2ecc71; 
            padding: 12px; 
            border-radius: 8px;
            text-align: center; 
            margin: 15px 0; 
            font-size: 14px;
            font-weight: 500;
        }

        .error { 
            background: rgba(220, 53, 69, 0.12); 
            border: 1px solid rgba(220, 53, 69, 0.25);
            color: #ea868f; 
            padding: 12px; 
            border-radius: 8px;
            text-align: center; 
            margin: 15px 0; 
            font-size: 14px;
            font-weight: 500;
        }

        label { 
            display: block; 
            margin: 16px 0 6px; 
            font-size: 13px;
            font-weight: 600; 
            color: #94a3b8; 
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Form Text Inputs */
        input { 
            width: 100%; 
            padding: 12px 14px; 
            border-radius: 8px; 
            border: 1px solid rgba(255, 255, 255, 0.15); 
            background: rgba(0, 0, 0, 0.3);
            color: #fff;
            font-size: 15px; 
            outline: none;
            transition: all 0.3s ease;
        }

        input:hover {
            border-color: rgba(255, 255, 255, 0.25);
        }

        input:focus {
            border-color: #0066cc;
            background: rgba(0, 0, 0, 0.4);
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.25);
        }

        /* Submit Button */
        .btn {
            width: 100%;
            padding: 14px;
            background: #ff8c00;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            margin-top: 25px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(255, 140, 0, 0.25);
        }

        .btn:hover {
            background: #d97706;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(255, 140, 0, 0.4);
        }

        .btn:active {
            transform: translateY(1px);
        }

        /* Cancel Link Button */
        .cancel-btn {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .cancel-btn:hover {
            color: #f8fafc;
        }
    </style>
</head>
<body>
    <!-- Top Header Navigation -->
    <div class="header">
        <div class="logo">🔧 DRILLTECH</div>
        <div class="user-welcome">Welcome, <?php echo htmlspecialchars($client_name); ?></div>
    </div>

    <!-- Form container -->
    <div class="content">
        <h2>Request New Project</h2>

        <?php if($success) echo "<p class='success'>$success</p>"; ?>
        <?php if($error) echo "<p class='error'>$error</p>"; ?>

        <form method="POST">
            <label>Project Name</label>
            <input type="text" name="project_name" required autocomplete="off">

            <label>Location</label>
            <input type="text" name="location" required>

            <label>Project Value (RM)</label>
            <input type="number" name="value" step="0.01" required>

            <button type="submit" class="btn">Submit Project Request</button>
        </form>

        <a href="projectCL.php" class="cancel-btn">← Cancel and Go Back</a>
    </div>
</body>
</html>