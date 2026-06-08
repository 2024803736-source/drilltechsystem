<?php
session_start();
include("database.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $clientID= $_POST['client_id'];
    $password= $_POST['password'];

    if($password === "7890"){
        $query="SELECT * FROM client WHERE Client_ID='$clientID'";
        $result= mysqli_query($conn,$query);

        if (mysqli_num_rows($result)==1){
            $row = mysqli_fetch_assoc($result);
            $_SESSION['client_id']= $row['Client_ID'];
            $_SESSION['client_name']= $row['Client_Name'];
            header("Location: client.php");
            exit();
        }else {
            $error = "Client is not exist!";
        }
    }else{
            $error = "Wrong Password!";
        
    }
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Login - DrillTech HDD</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.75)), 
                        url('backgroundCSC264.png') center/cover no-repeat fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .login-box {
            background: rgba(0, 64, 128, 0.95);
            padding: 40px 35px;
            border-radius: 16px;
            width: 420px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.6);
        }
        .logo {
            font-size: 52px;
            margin-bottom: 8px;
        }
        h1 { font-size: 26px; margin-bottom: 6px; }
        p.subtitle { margin-bottom: 30px; opacity: 0.9; font-size: 15px; }
        
        input {
            width: 100%;
            padding: 14px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            background: white;
            color: #333;
        }
        .btn {
            width: 100%;
            padding: 14px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn:hover { background: #218838; }
        .error { color: #ff6b6b; margin-top: 15px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="logo">🔧</div>
        <h1>DRILLTECH HDD</h1>
        <p class="subtitle">Client Portal</p>

        <form method="post">
            <input type="text" name="client_id" placeholder="Client ID" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn">LOG IN</button>
        </form>

        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    </div>
</body>
</html>