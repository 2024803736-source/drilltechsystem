<?php
session_start();
include("database.php");

if(!isset($_SESSION['username'])){
    header("Location: loginEM.php");
    exit();
}

$employeeID = $_SESSION['employee_id'];

// Ambil data employee dari DB
$result = mysqli_query($conn, "SELECT * FROM employee WHERE Employee_ID='$employeeID'");
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - DrillTech HDD</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(rgba(0, 77, 0, 0.45), rgba(15, 23, 18, 0.85)), 
                        url('backgroundCSC264.png') center/cover no-repeat fixed;
            color: #f8fafc;
            min-height: 100vh;
        }

        .header {
            background: #004d00;
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

        /* Logout Button */
        .logout-btn {
            color: #ff6b6b;
            font-weight: 600;
            text-decoration: none;
            padding: 6px 14px;
            border: 1px solid rgba(255, 107, 107, 0.3);
            border-radius: 6px;
            transition: all 0.2s;
        }
        .logout-btn:hover {
            background: rgba(255, 107, 107, 0.1);
            color: #ff5252;
        }

        .sidebar {
            width: 240px; 
            background: #004d00; 
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            position: fixed; 
            top: 64px;
            left: 0;
            height: calc(100vh - 64px); 
            padding-top: 20px;
            z-index: 90;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.15);
        }
        
        .sidebar a {
            display: flex; 
            align-items: center; 
            padding: 14px 25px; 
            color: rgba(255, 255, 255, 0.8); 
            text-decoration: none; 
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.2s ease;
            gap: 10px;
        }
        
        .sidebar a:hover { 
            color: #fff;
            background: #ff8c00; 
        }
        
        .sidebar a.active { 
            color: #fff;
            background: #ff8c00; 
        }

        .content { 
            margin-left: 260px; 
            padding: 94px 30px 40px 30px; 
        }

        .main-box {
            background: rgba(0, 0, 0, 0.65); 
            border: 1px solid rgba(0, 77, 0, 0.3);
            border-radius: 12px; 
            padding: 35px 30px;
            width: 100%;
            max-width: 520px;
            margin: 0 auto;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
        }

        .main-box h2 { 
            font-size: 20px;
            font-weight: 700;
            color: #fff; 
            margin-bottom: 25px; 
            text-align: center; 
            border-bottom: 2px solid #ff8c00;
            padding-bottom: 12px;
            letter-spacing: 0.5px;
        }

        .profile-img { 
            text-align: center; 
            margin-bottom: 28px; 
        }

        .profile-img img {
            width: 160px; 
            height: 160px; 
            border-radius: 50%; 
            border: 4px solid #004d00;
            object-fit: cover;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
        }

        .profile-details {
            margin-bottom: 25px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 15px;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #94a3b8;
            font-weight: 600;
        }

        .detail-value {
            color: #f8fafc;
            font-weight: 500;
            text-align: right;
        }

        .button-container { 
            text-align: center; 
            margin-top: 15px; 
        }

        .btn {
            display: inline-block;
            padding: 12px 24px; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 0.5px;
            transition: all 0.2s ease;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .edit-btn { 
            background: #2563eb;
            color: white; 
        }

        .edit-btn:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(37, 99, 235, 0.35);
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo">
            <img src="images/logo.png" alt="Logo" style="height: 65px; width: auto; display: block; object-fit: contain; filter: drop-shadow(0px 0px 8px rgba(255, 255, 255, 0.65));">
        </div>
        <div style="display:flex; align-items:center; gap:15px;">
            <div class="user-welcome">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></div>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="sidebar">
        <a href="employee.php">📊 DASHBOARD</a>
        <a href="projectEM.php">🔍 PROJECT</a>
        <a href="profileEM.php" class="active">👤 PROFILE</a>
        <a href="payrollEM.php">💰 PAYROLL</a>
    </div>

    <div class="content">
        <div class="main-box">
            <h2>Personal Details</h2>
            <div class="profile-img">
                <img src="images/gambar/<?php echo $employeeID; ?>.jpg" onerror="this.src='https://www.w3schools.com/howto/img_avatar.png';" alt="Profile Picture">
            </div>
            
            <div class="profile-details">
                <div class="detail-row">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($row['Employee_Name']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Contact:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($row['Employee_Contact']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Gender:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($row['Employee_Gender']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Position:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($row['Employee_Position']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Address:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($row['Employee_Address']); ?></span>
                </div>
            </div>

            <div class="button-container">
                <a href="editProfileEM.php" class="btn edit-btn">Edit Personal Details</a>
            </div>
        </div>
    </div>
</body>
</html>