<?php
session_start();
include("database.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $employeeID = $_POST['employee_id'];
    $password   = $_POST['password'];

    // Password default untuk semua employee
    if($password === "101"){
        // Cari employee dalam database ikut Employee_ID
        $query = "SELECT * FROM employee WHERE Employee_ID='$employeeID'";
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_assoc($result);
            // Simpan dalam session
            $_SESSION['employee_id']   = $row['Employee_ID'];
            $_SESSION['username']      = $row['Employee_Name']; 
            header("Location: employee.php");
            exit();
        } else {
            $error = "Employee ID tidak wujud!";
        }
    } else {
        $error = "Password salah! (Default: 101)";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Login - DrillTech HDD</title>
    <style>
        /* CSS Reset & Modern Typography */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            height: 100vh;
            background: linear-gradient(rgba(10, 25, 15, 0.8), rgba(5, 12, 8, 0.95)), 
                        url('images/construction_bg.jpg') center/cover no-repeat fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f8fafc;
            padding: 20px;
        }

        .login-box {
            background: rgba(15, 28, 20, 0.85); /* Clean dark-green glassmorphism */
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(40, 167, 69, 0.2);
            padding: 45px 35px;
            border-radius: 16px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        }

        /* Styled emoji logo badge */
        .logo-badge {
            font-size: 38px;
            background: rgba(40, 167, 69, 0.15);
            border: 1px solid rgba(40, 167, 69, 0.3);
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
            border-color: #28a745;
            background: rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.25);
        }

        /* Modern Button Styles */
        .btn {
            width: 100%;
            padding: 14px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 8px; /* Consistent rounded corners */
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 6px;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2);
        }

        .btn:hover { 
            background: #218838; 
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(40, 167, 69, 0.3);
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
        <div class="logo-badge">👷</div>
        <h1>DRILLTECH HDD</h1>
        <p class="subtitle">Employee Portal</p>

        <form method="post">
            <input type="text" name="employee_id" placeholder="Employee ID" required autocomplete="off">
            <input type="password" name="password" placeholder="Password (Default: 101)" required>
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