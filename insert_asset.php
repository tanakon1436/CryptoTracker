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


    $updated_quantity = round($quantity, 5);  


    $query = "SELECT quantity FROM assets WHERE asset_id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $asset_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($current_quantity);

    if ($stmt->num_rows > 0) {
        // หากมีข้อมูลในฐานข้อมูล เราจะได้ค่า current_quantity
        $stmt->fetch();

        // บวก quantity ใหม่เข้ากับที่เก่าที่เก็บไว้
        $new_quantity = $current_quantity + $updated_quantity;

        // อัปเดตฐานข้อมูล
        $update_query = "UPDATE assets SET quantity = ?, purchase_price = ? WHERE asset_id = ?";
        $update_stmt = $connection->prepare($update_query);
        $update_stmt->bind_param("ddi", $new_quantity, $purchase_price, $asset_id);
        $result = $update_stmt->execute();

        if ($result) {
            echo "<script>alert('Asset updated successfully!'); window.location='dashboard.php';</script>";
        } else {
            echo "<script>alert('Failed to update asset.'); window.location='dashboard.php';</script>";
        }

        $update_stmt->close();
    } else {
        echo "<script>alert('Asset not found.'); window.location='dashboard.php';</script>";
    }

    $stmt->close();
}

mysqli_close($connection);
?>
