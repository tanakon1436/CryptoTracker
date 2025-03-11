<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// เชื่อมต่อฐานข้อมูล
$connection = new mysqli("localhost", "root", "", "Crypto");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// ตรวจสอบว่า session มีค่าหรือไม่
if (isset($_SESSION['user_id'])) {
    // ถ้ามี session user_id แสดงว่าเป็นผู้ที่ล็อกอินแล้ว
    header("Location: dashboard.php"); // เปลี่ยนเส้นทางไปที่หน้า dashboard
    exit();
}

// ถ้าฟอร์มถูกส่งมา
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];  // รหัสผ่านที่กรอกมา
    $pass_see = $password;  // เก็บรหัสผ่านที่กรอกในรูปแบบธรรมดา

    // แฮชรหัสผ่านก่อนเก็บในฐานข้อมูล
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // เพิ่มข้อมูลผู้ใช้ลงในตาราง 'users'
    $query = "INSERT INTO users (fname, lname, age, username, email, password, pass_see) 
              VALUES ('$fname', '$lname', '$age', '$username', '$email', '$hashed_password', '$pass_see')";

    if (mysqli_query($connection, $query)) {
        $user_id = mysqli_insert_id($connection); // ดึง user_id ที่เพิ่งเพิ่ม

        // เพิ่มพอร์ตการลงทุน (portfolio) เริ่มต้นให้กับผู้ใช้
        $default_portfolio_name = "My First Portfolio";
        $portfolio_query = "INSERT INTO portfolios (user_id, portname) 
                            VALUES ('$user_id', '$default_portfolio_name')";

        if (mysqli_query($connection, $portfolio_query)) {
            // ดึง portfolio_id ที่เพิ่งเพิ่ม
            $portfolio_id = mysqli_insert_id($connection);

            // ดึง id ของ Bitcoin จากตาราง crypto
            $bitcoin_query = "SELECT asset_id FROM crypto WHERE asset_name = 'Bitcoin'";
            $bitcoin_result = mysqli_query($connection, $bitcoin_query);
            $bitcoin_data = mysqli_fetch_assoc($bitcoin_result);
            $bitcoin_id = $bitcoin_data['asset_id'];

            // เพิ่ม Bitcoin ในพอร์ตของผู้ใช้
            $insert_asset_query = "INSERT INTO assets (portfolio_id, asset_id, quantity, purchase_price) 
                                   VALUES ('$portfolio_id', '$bitcoin_id', 0, 0)";

            if (mysqli_query($connection, $insert_asset_query)) {
                echo "<script>alert('Register Complete! Portfolio and Bitcoin have been created.'); window.location='login.php'</script>";
            } else {
                echo "<script>alert('Error creating asset: " . mysqli_error($connection) . "');</script>";
            }
        } else {
            echo "<script>alert('Error creating portfolio: " . mysqli_error($connection) . "');</script>";
        }
    } else {
        echo "<script>alert('Error registering user: " . mysqli_error($connection) . "');</script>";
    }
}

mysqli_close($connection);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body style="margin: 10px; height: 100vh; display: flex; justify-content: center; align-items: center; background-color:#1B2028">
    <div class="card" style="width:500px;border-radius:20px;background-color:#31353F; color: white;">
        <div class="card-body" style="background-color:#31353F;width:500px;border-radius:20px;">
            <h3 class="card-title text-center"> Register</h3>
    
            <form method="POST" action="register.php">
                <div class="row">
                    <div class="col-md-6">
                        <label>Name:</label>
                        <input type="text" name="fname" required class="form-control" style="color: white; background-color: #31353F; border: 2px solid #555;border-radius:20px" placeholder="Type your name....">
                    </div>
                    <div class="col-md-6">
                        <label>Lastname:</label>
                        <input type="text" name="lname" required class="form-control" style="color: white; background-color: #31353F; border: 2px solid #555;border-radius:20px" placeholder="Type your lastname....">
                    </div>      
                </div>    
                <label>Age:</label>
                <input type="number" name="age" required class="form-control" style="color: white; background-color: #31353F; border: 2px solid #555;border-radius:20px" placeholder="Input your Age....">
                
                <label>Email:</label>
                <input type="email" name="email" required class="form-control" style="color: white; background-color: #31353F; border: 2px solid #555;border-radius:20px" placeholder="example@gmail.com">
                
                <label>Username:</label>
                <input type="text" name="username" required class="form-control" style="color: white; background-color: #31353F; border: 2px solid #555;border-radius:20px" placeholder="Make your username....">
                
                <label>Password:</label>
                <input type="password" name="password" required class="form-control" style="color: white; background-color: #31353F; border: 2px solid #555;border-radius:20px" placeholder="Make your Password....">
                
                
                <br>
                <button name="submit" type="submit" class="btn btn-success" style="border-radius:20px;width:100%">Register</button>
                <p style="justify-content:center">Already have an account? <a href="login.php" class="text-success">Login</a></p>
            </form>
        </div>
    </div>
</body>
</html>
