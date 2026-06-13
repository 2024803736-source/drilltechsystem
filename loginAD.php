<?php
session_start();
include("database.php"); // sambung ke database

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $adminID  = $_POST['admin_id'];
    $password = $_POST['password'];

    // Cari admin dalam database ikut Admin_ID & Admin_Password
    $query  = "SELECT * FROM admin WHERE Admin_ID='$adminID' AND Admin_Password='$password'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);
        // Simpan dalam session
        $_SESSION['admin_id']   = $row['Admin_ID'];
        $_SESSION['admin_name'] = "Admin " . $row['Admin_ID']; 
        header("Location: admin.php"); // redirect ke dashboard
        exit();
    } else {
        $error = "ID atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - DrillTech HDD</title>
    <style>
        /* CSS Reset & Modern Typography */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            height: 100vh;
            /* Grey-tinted background overlay to match the admin theme */
            background: linear-gradient(rgba(80, 80, 80, 0.45), rgba(15, 15, 15, 0.95)), 
                        url('images/construction_bg.jpg') center/cover no-repeat fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f8fafc;
            padding: 20px;
        }

        .login-box {
            /* Semi-transparent dark grey glassmorphism matching your admin theme */
            background: rgba(45, 45, 45, 0.88); 
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(120, 120, 120, 0.25);
            padding: 45px 35px;
            border-radius: 16px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        }

        /* Styled emoji logo badge with grey tint */
        .logo-badge {
            font-size: 38px;
            background: rgba(120, 120, 120, 0.2);
            border: 1px solid rgba(120, 120, 120, 0.35);
            width: 72px;
            height: 72px;
            border-radius: 50%;
            margin: 0 auto 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        h1 { 
            font-size: 24px; 
            font-weight: 700; 
            letter-spacing: 1px; 
            margin-bottom: 6px;
            color: #fff;
        }

        p.subtitle { 
            margin-bottom: 28px; 
            color: #94a3b8; 
            font-size: 14px; 
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        
        form {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        /* Modern Input Styles */
        input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            font-size: 15px;
            background: rgba(0, 0, 0, 0.3);
            color: #fff;
            outline: none;
            transition: all 0.3s ease;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        input:hover {
            border-color: rgba(255, 255, 255, 0.25);
            background: rgba(0, 0, 0, 0.4);
        }

        input:focus {
            border-color: #888888;
            background: rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 0 3px rgba(120, 120, 120, 0.25);
        }

        /* Modern Button Styles (using your grey admin theme) */
        .btn {
            width: 100%;
            padding: 14px;
            background: #555555;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 6px;
            box-shadow: 0 4px 12px rgba(85, 85, 85, 0.3);
        }

        .btn:hover { 
            background: #3c3c3c; 
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(85, 85, 85, 0.45);
        }

        .btn:active {
            transform: translateY(1px);
        }

        /* Professional Alert Box for Errors */
        .error-alert { 
            background: rgba(220, 53, 69, 0.15);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #ea868f;
            padding: 12px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="logo-badge">🔧</div>
        <h1>DRILLTECH HDD</h1>
        <p class="subtitle">Admin Portal</p>

        <form method="post">
            <input type="text" name="admin_id" placeholder="Admin ID" required autocomplete="off">
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn">LOG IN</button>
        </form>

        <?php if(isset($error)): ?>
            <div class="error-alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>