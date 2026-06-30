<?php
session_start();
include("database.php");

if(!isset($_SESSION['client_id'])){
    header("Location: loginCL.php");
    exit();
}

$client_id = $_SESSION['client_id'];
$client_name = $_SESSION['client_name'];

// Semak jika ada project_id yang dihantar dari page paymentCL.php
if(!isset($_GET['project_id'])){
    header("Location: paymentCL.php");
    exit();
}

$selected_project_id = intval($_GET['project_id']);

/**
 * AMBIL DATA PROJEK YANG DIPILIH SAHAJA
 * Tarik nama dan nilai harga projek terus dari pangkalan data berdasarkan ID projek tersebut.
 */
$project_query = mysqli_query($conn, "
    SELECT Project_ID, Project_Name, Project_Value 
    FROM project 
    WHERE Project_ID = $selected_project_id AND Client_ID = $client_id
");

// Jika projek tidak wujud atau bukan milik client ini
if(mysqli_num_rows($project_query) == 0){
    header("Location: paymentCL.php");
    exit();
}

$project_data = mysqli_fetch_assoc($project_query);
$project_name = $project_data['Project_Name'];
$amount = floatval($project_data['Project_Value']);

$success = "";
$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $method = mysqli_real_escape_string($conn, $_POST['method']);

    if($amount <= 0){
        $error = "Payment amount must be greater than zero!";
    } else {
        // Masukkan rekod pembayaran baharu
        $sql = "INSERT INTO payment (Payment_Method, Payment_Date, Payment_Status, Payment_Time, Project_ID) 
                VALUES ('$method', CURDATE(), 'Completed', CURTIME(), $selected_project_id)";

        if(mysqli_query($conn, $sql)){
            $_SESSION['payment_success'] = "Payment of RM " . number_format($amount, 2) . " for project '$project_name' was successful!";
            header("Location: paymentCL.php");
            exit();
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
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(rgba(0, 48, 135, 0.45), rgba(15, 20, 30, 0.9)), 
                        url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: #f8fafc;
            min-height: 100vh;
        }
        .header {
            background: #003087;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }
        .content {
            max-width: 650px;
            margin: 40px auto;
            background: rgba(0,0,0,0.85);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5);
        }
        h2 { text-align: center; margin-bottom: 25px; border-bottom: 2px solid #ff8c00; padding-bottom: 10px;}
        .error { color: #ff6b6b; text-align: center; margin: 15px 0; font-weight: 600; }
        label { display: block; margin: 15px 0 8px; font-weight: bold; color: #ffcc00;}
        select, input { width: 100%; padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); font-size: 16px; background: rgba(255,255,255,0.1); color: #fff;}
        select option { background: #1e1e1e; color: #fff; }
        
        input[readonly] {
            background: rgba(255, 255, 255, 0.05);
            color: #ffcc00;
            font-weight: 600;
            cursor: not-allowed;
            border: 1px dashed rgba(255,255,255,0.1);
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
            transition: all 0.2s ease;
        }
        .btn:hover { background: #218838; transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🔧 DRILLTECH</div>
        <div>Welcome, <?php echo htmlspecialchars($client_name); ?></div>
    </div>

    <div class="content">
        <h2>Confirm Payment Details</h2>

        <?php if($error) echo "<p class='error'>⚠️ $error</p>"; ?>

        <form method="POST">
            <label>Selected Project</label>
            <input type="text" readonly value="#<?php echo $selected_project_id; ?> - <?php echo htmlspecialchars($project_name); ?>">

            <label>Payment Amount (RM)</label>
            <input type="text" readonly value="RM <?php echo number_format($amount, 2); ?>">

            <label>Payment Method</label>
            <select name="method" required>
                <option value="Online Banking">Online Banking</option>
                <option value="Cheque">Cheque</option>
                <option value="Cash">Cash</option>
            </select>

            <button type="submit" class="btn">Confirm & Make Payment</button>
        </form>

        <p style="text-align:center; margin-top:25px;">
            <a href="paymentCL.php" style="color:#1E90FF; text-decoration:none;">← Cancel & Go Back</a>
        </p>
    </div>
</body>
</html>