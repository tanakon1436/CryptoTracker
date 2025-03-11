<?php
session_start();
$conn = new mysqli("localhost", "root", "", "Crypto");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id']; // ดึง user_id ของผู้ใช้ที่ล็อกอิน
    $portfolio_id = $_POST['portfolio_id']; // ดึง portfolio_id ที่ส่งมาจากฟอร์ม
    $asset_id = $_POST['asset_id']; // ดึง asset_id ที่ต้องการลบ

    $query = "SELECT portfolio_id FROM portfolios WHERE user_id = ? AND portfolio_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $portfolio_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "<script>alert('This portfolio does not belong to you!'); window.location.href='dashboard.php';</script>";
        exit();
    }

    // ลบ asset ออกจาก portfolio
    $query = "DELETE FROM assets WHERE portfolio_id = ? AND asset_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $portfolio_id, $asset_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php?portfolio_id=" . $portfolio_id);
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
