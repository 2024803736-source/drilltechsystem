<?php
session_start();
if(!isset($_SESSION['admin_id'])){ header("Location: loginAD.php"); exit(); }
?>
<!DOCTYPE html>
<html>
<head><title>Project Equipment</title></head>
<body>
  <h2>Project Equipment</h2>
  <p><strong>Project ID:</strong> A03 | <strong>Project Name:</strong> Site Alpha | <strong>Client:</strong> MegaBuild</p>
  <h3>Machine Type</h3>
  <select><option>M-001 Goodong</option><option>M-002 Backhoe</option><option>M-003 Crane</option></select>
  <h3>Pipe Quantity</h3>
  <input type="number" placeholder="Enter Quantity of Pipe">
  <h3>Duration</h3>
  <input type="date">
  <button>Save</button>
</body>
</html>
