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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - DrillTech HDD</title>
    <style>
        /* Reset & Layout Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(rgba(0, 77, 0, 0.45), rgba(15, 23, 18, 0.85)), 
                        url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        /* Form Container Box */
        .form-container {
            background: rgba(0, 0, 0, 0.65);
            border: 1px solid rgba(0, 77, 0, 0.3);
            padding: 35px 30px;
            border-radius: 12px;
            width: 100%;
            max-width: 440px;
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

        label {
            display: block;
            margin-top: 16px;
            font-size: 13px;
            font-weight: 600;
            color: #94a3b8;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Form Text Inputs */
        input[type="text"] {
            width: 100%;
            padding: 12px 14px;
            margin-top: 6px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: rgba(0, 0, 0, 0.3);
            color: #fff;
            font-size: 15px;
            outline: none;
            transition: all 0.3s ease;
        }

        input[type="text"]:hover {
            border-color: rgba(255, 255, 255, 0.25);
        }

        input[type="text"]:focus {
            border-color: #28a745;
            background: rgba(0, 0, 0, 0.4);
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.25);
        }

        /* Submit Button */
        input[type="submit"] {
            margin-top: 25px;
            padding: 13px;
            border: none;
            border-radius: 8px;
            background-color: #28a745; /* Green submit action button */
            color: white;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.5px;
            cursor: pointer;
            width: 100%;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2);
        }

        input[type="submit"]:hover {
            background-color: #218838;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(40, 167, 69, 0.35);
        }

        input[type="submit"]:active {
            transform: translateY(1px);
        }

        /* Cancel Link Button */
        .cancel-btn {
            display: block;
            text-align: center;
            margin-top: 15px;
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
    <div class="form-container">
        <h2>Edit Personal Details</h2>
        <form method="post">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($row['Employee_Name']); ?>" required autocomplete="off">

            <label>Contact:</label>
            <input type="text" name="contact" value="<?php echo htmlspecialchars($row['Employee_Contact']); ?>" required>

            <label>Gender:</label>
            <input type="text" name="gender" value="<?php echo htmlspecialchars($row['Employee_Gender']); ?>" required>

            <label>Position:</label>
            <input type="text" name="position" value="<?php echo htmlspecialchars($row['Employee_Position']); ?>" required>

            <label>Address:</label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($row['Employee_Address']); ?>" required>

            <input type="submit" value="Save Changes">
        </form>
        <a href="profileEM.php" class="cancel-btn">Cancel</a>
    </div>
</body>
</html>