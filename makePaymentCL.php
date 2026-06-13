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
    <title>Make Payment - DrillTech HDD</title>
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
            line-height: 1.5;
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

        /* Form Text Inputs & Dropdowns */
        select, input { 
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

        select option {
            background-color: #0c1220; /* Dark slate dropdown options */
            color: white;
        }

        select:hover, input:hover {
            border-color: rgba(255, 255, 255, 0.25);
        }

        select:focus, input:focus {
            border-color: #0066cc;
            background: rgba(0, 0, 0, 0.4);
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.25);
        }

        /* Submit Button */
        .btn {
            width: 100%;
            padding: 14px;
            background: #28a745; /* Green color for positive transaction action */
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 700;
            margin-top: 25px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.25);
        }

        .btn:hover { 
            background: #218838; 
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(40, 167, 69, 0.4);
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
        <div>Welcome, <?php echo htmlspecialchars($client_name); ?></div>
    </div>

    <!-- Form container -->
    <div class="content">
        <h2>Make New Payment</h2>

        <?php if($success) echo "<p class='success'>$success <br><small style='opacity: 0.85;'>Redirecting back to payment history...</small></p>"; ?>
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

        <a href="paymentCL.php" class="cancel-btn">← Cancel and Go Back</a>
    </div>
</body>
</html>