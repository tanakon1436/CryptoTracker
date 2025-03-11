<?php
session_start();
$connection = mysqli_connect('localhost', 'root', '', 'Crypto');

if (!$connection) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username' ";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true); 
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            echo "<script>alert('Login Complete!');window.location='dashboard.php'</script>";
            exit();
        } else {
                echo "<script>alert('Incorrect username or password!'); window.location='login.php'</script>";
        } 
    }else{
        echo "<script>alert('Incorrect username or password!'); window.location='login.php'</script>";
    }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="app.js"></script>
</head>
<body style="background-color:#1B2028">
    <div class="container mt-5">
    <div class="card mx-auto" style="width:500px;border-radius:20px;background-color:#31353F; color: white;">
        <div class="card-body" style="background-color:#31353F;width:500px;border-radius:20px;">
            <h3 class="card-title text-center"> Login</h3>
            <form method="POST" action="login.php" class="mx-auto" style="max-width: 400px;">
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" required class="form-control" placeholder="Type your username..." style="color: white; background-color: #31353F; border: 2px solid #555;border-radius:20px">
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required class="form-control" placeholder="Type your password..." style="color: white; background-color: #31353F; border: 2px solid #555;border-radius:20px">
                </div>
                <button type="submit" class="btn btn-success btn-block" style="border-radius:20px">Login</button>
                <p style="justify-content:center">Don't have an account? <a href="register.php" class="text-success">Register</a></p>
                
        </div>
    </div>
        </form>
    </div>
</body>
</html>
