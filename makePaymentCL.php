<?php
session_start();
include("database.php");

if(!isset($_SESSION['client_id'])){
    header("Location: loginCL.php");
    exit();
}

$client_id = $_SESSION['client_id'];
$client_name = $_SESSION['client_name'];

// Get client's projects
$projects = mysqli_query($conn, "SELECT * FROM project WHERE Client_ID = $client_id");

$success = "";
$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $project_id = intval($_POST['project_id']);
    $amount = floatval($_POST['amount']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);

    if($amount <= 0){
        $error = "Payment amount must be greater than zero!";
    } else {
        // Insert payment
        $sql = "INSERT INTO payment (Payment_Method, Payment_Date, Payment_Status, Payment_Time, Project_ID) 
                VALUES ('$method', CURDATE(), 'Completed', CURTIME(), $project_id)";

        if(mysqli_query($conn, $sql)){
            $success = "Payment of RM " . number_format($amount, 2) . " was successful!";
            // Auto redirect after 3 seconds
            header("refresh:3;url=paymentCL.php");
        } else {
            $error = "Payment failed: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Payment - DrillTech</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.65)), url('ss1.jpg') center/cover no-repeat fixed;
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
            max-width: 650px;
            margin: 40px auto;
            background: rgba(0,0,0,0.85);
            padding: 40px;
            border-radius: 12px;
        }
        h2 { text-align: center; margin-bottom: 25px; }
        .success { 
            color: #28a745; 
            text-align: center; 
            font-size: 18px; 
            margin: 15px 0; 
            padding: 15px;
            background: rgba(40, 167, 69, 0.2);
            border-radius: 8px;
        }
        .error { 
            color: #ff6b6b; 
            text-align: center; 
            margin: 15px 0; 
            padding: 15px;
            background: rgba(255, 107, 107, 0.2);
            border-radius: 8px;
        }
        label { display: block; margin: 15px 0 8px; font-weight: bold; }
        select, input { 
            width: 100%; 
            padding: 12px; 
            border-radius: 8px; 
            border: none; 
            font-size: 16px; 
        }
        .btn {
            width: 100%;
            padding: 15px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 18px;
            font-weight: bold;
            margin-top: 25px;
            cursor: pointer;
        }
        .btn:hover { background: #218838; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🔧 DRILLTECH</div>
        <div>Welcome, <?php echo htmlspecialchars($client_name); ?></div>
    </div>

    <div class="content">
        <h2>Make New Payment</h2>

        <?php if($success) echo "<p class='success'>$success <br><small>Redirecting back to payment history...</small></p>"; ?>
        <?php if($error) echo "<p class='error'>$error</p>"; ?>

        <form method="POST">
            <label>Select Project</label>
            <select name="project_id" required>
                <option value="">-- Choose Project --</option>
                <?php while($row = mysqli_fetch_assoc($projects)): ?>
                    <option value="<?php echo $row['Project_ID']; ?>">
                        <?php echo htmlspecialchars($row['Project_Name']); ?> 
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Payment Amount (RM)</label>
            <input type="number" name="amount" step="0.01" min="1" required placeholder="Enter amount">

            <label>Payment Method</label>
            <select name="method" required>
                <option value="Online Banking">Online Banking</option>
                <option value="Cheque">Cheque</option>
                <option value="Cash">Cash</option>
            </select>

            <button type="submit" class="btn">Confirm & Make Payment</button>
        </form>

        <p style="text-align:center; margin-top:25px;">
            <a href="paymentCL.php" style="color:#1E90FF;">← Back to Payment History</a>
        </p>
    </div>
</body>
</html>