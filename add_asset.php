<?php
session_start();
$conn = new mysqli("localhost", "root", "", "Crypto");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id']; // ดึง user_id ของผู้ใช้ที่ล็อกอิน
    $asset_id = $_POST['asset_id']; // รับค่า asset_id จากปุ่มที่กด

    // ดึง portfolio_id ของผู้ใช้จากฐานข้อมูล
    $query = "SELECT portfolio_id FROM portfolios WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // หากพบ portfolio_id ให้ใช้ portfolio_id ตัวแรก
        $portfolio = $result->fetch_assoc();
        $portfolio_id = $portfolio['portfolio_id'];
    } else {
        echo "ไม่พบ portfolio สำหรับผู้ใช้นี้";
        exit();
    }

    // ตรวจสอบว่าสินทรัพย์มีอยู่ใน portfolio นี้หรือยัง
    $query = "SELECT * FROM assets WHERE portfolio_id = ? AND asset_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $portfolio_id, $asset_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // ถ้ายังไม่มี ให้เพิ่มเข้า assets (quantity เริ่มต้นเป็น 0)
        $query = "INSERT INTO assets (portfolio_id, asset_id, quantity, purchase_price) 
                  SELECT ?, asset_id, 0, price_live FROM crypto WHERE asset_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $portfolio_id, $asset_id);

        if ($stmt->execute()) {
            // รีไดเร็กไปที่หน้าหลักหรือหน้า dashboard
            header("Location: dashboard.php?portfolio_id=" . $portfolio_id); 
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "<script>alert('This asset is already in your portfolio!'); window.location.href='dashboard.php?portfolio_id=" . $portfolio_id . "';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
