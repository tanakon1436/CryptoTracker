<?php
$connection = mysqli_connect('localhost', 'root', '', 'Crypto');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!$connection) {
    die("🔴 Database Connection Failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['asset_id'])) {
    $asset_id = $_POST['asset_id'];
    $quantity = $_POST['quantity'];  
    $purchase_price = $_POST['purchase_price']; 


    if (!is_numeric($quantity) || !is_numeric($purchase_price)) {
        die("Input values are not valid numbers");
    }
    


    $updated_quantity = round($quantity, 5);  // ปัดเศษ 5 ตำแหน่ง


    $query = "UPDATE assets 
              SET quantity = ?, purchase_price = ? 
              WHERE asset_id = ?";


    $stmt = $connection->prepare($query);
    $stmt->bind_param("ddi", $updated_quantity, $purchase_price, $asset_id);

   
    $result = $stmt->execute();

    if ($result) {
        echo "<script>alert('Asset updated successfully!'); window.location='dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to update asset.'); window.location='dashboard.php';</script>";
    }

    $stmt->close();
}

mysqli_close($connection);
?>
