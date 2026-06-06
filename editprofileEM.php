<?php
session_start();
include("database.php");

if(!isset($_SESSION['employee_id'])){
    header("Location: loginEM.php");
    exit();
}

$employeeID = $_SESSION['employee_id'];

// Ambil data sedia ada
$result = mysqli_query($conn, "SELECT * FROM employee WHERE Employee_ID='$employeeID'");
$row = mysqli_fetch_assoc($result);

// Update bila submit form
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $gender = $_POST['gender'];
    $position = $_POST['position'];
    $address = $_POST['address'];

    mysqli_query($conn, "UPDATE employee 
        SET Employee_Name='$name', Employee_Contact='$contact', Employee_Gender='$gender', 
            Employee_Position='$position', Employee_Address='$address' 
        WHERE Employee_ID='$employeeID'");

    // Redirect balik ke profile
    header("Location: profileEM.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <style>
        body {
            background-image: url('images/construction_bg.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
            color: white;
            margin: 0;
        }
        .form-container {
            background-color: rgba(0,0,0,0.6);
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            margin: 50px auto;
        }
        h2 {
            color: #FFD700;
            text-align: center;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: none;
        }
        input[type="submit"] {
            margin-top: 20px;
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            background-color: #32CD32;
            color: white;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #228B22;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Personal Details</h2>
        <form method="post">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo $row['Employee_Name']; ?>">

            <label>Contact:</label>
            <input type="text" name="contact" value="<?php echo $row['Employee_Contact']; ?>">

            <label>Gender:</label>
            <input type="text" name="gender" value="<?php echo $row['Employee_Gender']; ?>">

            <label>Position:</label>
            <input type="text" name="position" value="<?php echo $row['Employee_Position']; ?>">

            <label>Address:</label>
            <input type="text" name="address" value="<?php echo $row['Employee_Address']; ?>">

            <input type="submit" value="Save Changes">
        </form>
    </div>
</body>
</html>
