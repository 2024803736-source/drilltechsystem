<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
if(!isset($_SESSION['admin_id'])){
    header("Location: loginAD.php");
    exit();
}
include("database.php");

if(!isset($_GET['id'])) die("No project selected.");

$projectID  = $_GET['id'];
$project    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM project WHERE Project_ID='$projectID'"));
$client     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM client WHERE Client_ID='".$project['Client_ID']."'"));
$payments   = mysqli_query($conn, "SELECT * FROM payment WHERE Project_ID='$projectID'");
$assigned   = mysqli_query($conn, "SELECT e.Employee_Name, e.Employee_Position 
                                   FROM assigned_employee ae 
                                   JOIN employee e ON ae.Employee_ID=e.Employee_ID 
                                   WHERE ae.Project_ID='$projectID'");
$equipments = mysqli_query($conn, "SELECT eq.Equipment_Name, eu.Equipment_Duration 
                                   FROM equipment_usage eu 
                                   JOIN equipment eq ON eu.Equipment_ID=eq.Equipment_ID 
                                   WHERE eu.Project_ID='$projectID'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Project Report</title>
    <style>
        body { 
            font-family:Arial,sans-serif; 
            background:#f0f0f0; 
            color:#222; 
            margin:0;
         }
        .top-bar { 
            background:#4a4a4a; 
            padding:12px 30px; 
            display:flex; a
            lign-items:center; 
            justify-content:space-between; 
        }
        .top-bar span { 
            color:white; 
            font-size:16px; 
            font-weight:bold; 
        }
        .top-bar button { 
            padding:8px 20px; 
            background:#888; 
            color:white; 
            border:none; 
            border-radius:5px; 
            font-size:14px; 
            cursor:pointer; 
        }
        .top-bar button:hover { 
            background:#555; 
        }
        .report { 
            max-width:800px; 
            margin:30px auto; 
            background:white; 
            padding:30px; border-radius:10px; 
            box-shadow:0 4px 15px rgba(0,0,0,0.15); }
        h1 { 
            font-size:24px; 
            margin-bottom:10px; 
        }
        h2 { 
            font-size:18px; 
            margin:20px 0 10px; 
            background:#ddd; 
            padding:8px; 
            border-radius:5px; 
        }
        p, li { 
            font-size:14px; 
            margin:5px 0; 
        }
        ul { 
            margin:0; 
            padding-left:20px; }
        .footer { 
            margin-top:30px; 
            font-size:12px; 
            color:#555; t
            ext-align:center; 
        }
    </style>
</head>
<body>

<div class="top-bar">
    <span>🔧 DRILLTECH — Project Report</span>
    <button onclick="window.close()">✕ Close</button>
</div>

<div class="report">
    <h1>PROJECT REPORT</h1>
    <p><strong>Project:</strong> <?php echo $project['Project_Name']; ?> (ID: <?php echo $project['Project_ID']; ?>)</p>
    <p><strong>Location:</strong> <?php echo $project['Project_Location']; ?></p>
    <p><strong>Client:</strong> <?php echo $client ? $client['Client_Name'] : $project['Client_ID']; ?></p>
    <p><strong>Value:</strong> RM<?php echo number_format($project['Project_Value'],2); ?></p>
    <p><strong>Status:</strong> <?php echo $project['Project_Status']; ?></p>

    <h2>Assigned Employees</h2>
    <ul>
        <?php if(mysqli_num_rows($assigned) > 0){ 
            while($emp = mysqli_fetch_assoc($assigned)){ ?>
                <li><?php echo $emp['Employee_Name']; ?> (<?php echo $emp['Employee_Position']; ?>)</li>
            <?php } 
        } else { ?>
            <li>No employees assigned.</li>
        <?php } ?>
    </ul>

    <h2>Equipment Usage</h2>
    <ul>
        <?php if(mysqli_num_rows($equipments) > 0){ 
            while($eq = mysqli_fetch_assoc($equipments)){ ?>
                <li><?php echo $eq['Equipment_Name']; ?> — Duration: <?php echo $eq['Equipment_Duration']; ?></li>
            <?php } 
        } else { ?>
            <li>No equipment used.</li>
        <?php } ?>
    </ul>

    <h2>Payment Summary</h2>
    <ul>
        <?php if(mysqli_num_rows($payments) > 0){ 
            while($pay = mysqli_fetch_assoc($payments)){ ?>
                <li>
                    <?php echo $pay['Payment_Method']; ?> | 
                    <?php echo $pay['Payment_Date']; ?> | 
                    <?php echo $pay['Payment_Status']; ?>
                    <?php if(isset($pay['Payment_Amount'])){ ?>
                        | RM<?php echo $pay['Payment_Amount']; ?>
                    <?php } ?>
                </li>
            <?php } 
        } else { ?>
            <li>No payments recorded.</li>
        <?php } ?>
    </ul>

    <h2>Sign-off</h2>
    <p>Prepared by: Admin</p>
    <p>Approved by: Manager</p>

    <div class="footer">
        DRILLTECH | Generated on <?php echo date("d M Y, h:i A"); ?>
    </div>
</div>

</body>
</html>
