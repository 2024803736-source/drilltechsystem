<?php
session_start();
include("database.php");

if(!isset($_SESSION['client_id'])){
    header("Location: loginCL.php");
    exit();
}

$client_id = $_SESSION['client_id'];
$client_name = $_SESSION['client_name'];

$projects = mysqli_query($conn, "SELECT Project_ID, Project_Name, Project_Value FROM project WHERE Client_ID = $client_id");

$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $project_id = intval($_POST['project_id']);
    $amount = floatval($_POST['amount']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);

    if($amount <= 0){
        $error = "Payment amount must be greater than zero!";
    } else {
        // Kekalkan penyimpanan input amaun terus ke pangkalan data
        $sql = "INSERT INTO payment (Payment_Method, Payment_Date, Payment_Status, Payment_Time, Project_ID, Payment_Value) 
                VALUES ('$method', CURDATE(), 'Completed', CURTIME(), $project_id, $amount)";

        if(mysqli_query($conn, $sql)){
            $_SESSION['payment_success'] = "Payment of RM " . number_format($amount, 2) . " was successful!";
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
    <title>Make Payment - DrillTech HDD</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(rgba(0, 48, 135, 0.45), rgba(15, 20, 30, 0.9)), 
                        url('images/construction_bg.jpg') center/cover no-repeat fixed;
            color: #f8fafc; min-height: 100vh;
        }
        .header {
            background: #003087; padding: 0 30px; height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
        }
        .content {
            background: rgba(0, 0, 0, 0.65); border: 1px solid rgba(0, 48, 135, 0.35);
            padding: 35px 30px; border-radius: 12px;
            width: 100%; max-width: 480px; margin: 110px auto 40px;
        }
        h2 { font-size: 20px; color: #fff; text-align: center; margin-bottom: 25px; border-bottom: 2px solid #ff8c00; padding-bottom: 10px;}
        .error { background: rgba(220, 53, 69, 0.12); color: #ea868f; padding: 12px; border-radius: 8px; text-align: center; margin: 15px 0; }
        label { display: block; margin: 16px 0 6px; font-size: 13px; color: #94a3b8; text-transform: uppercase; }
        select, input { width: 100%; padding: 12px 14px; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.15); background: rgba(0, 0, 0, 0.3); color: #fff; font-size: 15px; outline: none;}
        .btn { width: 100%; padding: 14px; background: #28a745; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 700; margin-top: 25px; cursor: pointer; }
        .cancel-btn { display: block; text-align: center; margin-top: 20px; color: #94a3b8; text-decoration: none; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🔧 DRILLTECH</div>
        <div>Welcome, <?php echo htmlspecialchars($client_name); ?></div>
    </div>

    <div class="content">
        <h2>Make New Payment</h2>
        <?php if($error) echo "<p class='error'>$error</p>"; ?>

        <form method="POST">
            <label>Select Project</label>
            <select name="project_id" id="project_select" onchange="autoFillAmount()" required>
                <option value="">-- Choose Project --</option>
                <?php while($row = mysqli_fetch_assoc($projects)): ?>
                    <option value="<?php echo $row['Project_ID']; ?>" data-price="<?php echo $row['Project_Value']; ?>">
                        <?php echo htmlspecialchars($row['Project_Name']); ?> 
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Payment Amount (RM)</label>
            <input type="number" name="amount" id="amount_input" step="0.01" min="0.01" required placeholder="Enter amount">

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

    <script>
    function autoFillAmount() {
        var select = document.getElementById("project_select");
        var selectedOption = select.options[select.selectedIndex];
        var projectPrice = selectedOption.getAttribute("data-price");
        var amountField = document.getElementById("amount_input");
        
        if (projectPrice) {
            // Membantu mencadangkan jumlah nilai penuh asal di kotak input
            amountField.value = parseFloat(projectPrice).toFixed(2);
        } else {
            amountField.value = "";
        }
    }
    </script>
</body>
</html>