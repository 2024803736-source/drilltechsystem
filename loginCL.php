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
            $_SESSION['username']= $row['Client_Name'];
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
<!DOCTYPE html>
<html>
<head>
    <title>Client Login</title>
</head>
<body>
    <h2>Login Client</h2>
    <form method="post">
        <label>Client ID:</label><br>
        <input type="text" name="client_id" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
