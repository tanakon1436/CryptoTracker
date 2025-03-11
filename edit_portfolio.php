<?php
session_start();
$connection = mysqli_connect('localhost', 'root', '', 'Crypto');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!$connection) {
    die("🔴 Database Connection Failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['portfolio_id']) && isset($_POST['portname'])) {
    $user_id = $_SESSION['user_id']; 
    $portfolio_id = $_POST['portfolio_id'];  // ดึง portfolio_id ที่ส่งมาจากฟอร์ม
    $portname = $_POST['portname']; // รับชื่อใหม่ของ portfolio จากฟอร์ม

    // ตรวจสอบว่า portfolio_id เป็นของ user นี้หรือไม่
    $query = "SELECT portfolio_id, user_id FROM portfolios WHERE portfolio_id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $portfolio_id);
    $stmt->execute();
    $result = $stmt->get_result();



    // อัพเดตชื่อ portfolio
    $query = "UPDATE portfolios SET portname = ? WHERE portfolio_id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("si", $portname, $portfolio_id);

    if ($stmt->execute()) {
        echo "<script>alert('Portfolio name updated successfully!'); window.location='dashboard.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating portfolio name: " . $stmt->error . "'); window.location='dashboard.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid request. Please try again.'); window.location='dashboard.php';</script>";
}

mysqli_close($connection);
?>
