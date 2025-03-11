<?php
function getUserAssets($user_id) {
    $conn = new mysqli("localhost", "root", "", "Crypto");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT c.asset_id, c.asset_name, c.price_live, c.image_url, c.short_name, a.quantity,a.purchase_price,a.portfolio_id
            FROM assets a
            JOIN crypto c ON a.asset_id = c.asset_id
            JOIN portfolios p ON a.portfolio_id = p.portfolio_id
            WHERE p.user_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $assets = [];
    while ($row = $result->fetch_assoc()) {
        $assets[] = [
            'asset_id'   => (int) $row['asset_id'], // แปลงให้เป็น int
            'asset_name' => htmlspecialchars($row['asset_name']),
            'price_live' => (float) $row['price_live'], // แปลงให้เป็น float
            'image_url'  => htmlspecialchars($row['image_url']),
            'short_name' => htmlspecialchars($row['short_name']),
            'quantity'   => (float) $row['quantity'], // แปลงให้เป็น float,\
            'purchase_price' => (float) $row['purchase_price'],
            'portfolio_id'   => (int) $row['portfolio_id'] // เพิ่ม portfolio_id
        ];
    }

    $stmt->close();
    $conn->close();

    return $assets;
}
?>
