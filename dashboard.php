<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

include 'list_port.php';
$assets = getUserAssets($user_id);
foreach ($assets as $asset) {
    // ดึง portfolio_id ของแต่ละ asset
    $portfolio_id = $asset['portfolio_id'];
}


$conn = new mysqli("localhost", "root", "", "Crypto");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// ดึงข้อมูลชื่อและนามสกุลของผู้ใช้
$query = "SELECT fname, lname FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    $fullname = $user_data['fname'] . " " . $user_data['lname'];
} else {
    $fullname = "Unknown User";
}
$stmt->close();

// ดึงชื่อ Portfolio
$query = "SELECT portname,portfolio_id FROM portfolios WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$portfolioname = ($result->num_rows > 0) ? $result->fetch_assoc()['portname'] : "No Portfolio Name";
$stmt->close();
$conn->close();



$requiredAssetTypes = ["Bitcoin", "Ethereum", "Dogecoin"];
$existingAssets = array_column($assets, 'asset_name');
$missingAssets = array_diff($requiredAssetTypes, $existingAssets);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DashBoard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .full-height {
            height: 100vh;
        }
        .user-info {
            position: absolute;
            top: 10px;
            right: 20px;
            color: white;
            font-size: 16px;
            font-weight: bold;
        }
        .but:hover {
            transform: scale(1.3); 
            cursor: pointer; 
        }
        .btn:hover { 
            transform: scale(1.1); 
            cursor: pointer; 
        }
        .btn-close {
            filter: invert(1); /* เปลี่ยนสีปุ่มกากบาทเป็นสีขาว */
        }
        
        .btn-hover-effect {
            background-color: #31353F;
            color: white;
            border-radius: 10px;
            transition: background-color 0.3s, color 0.3s; /* เพิ่ม transition สำหรับการเปลี่ยนแปลง */
        }
        .btn{
            color:white;
        }
        .btn-hover-effect:hover {
            background-color: rgba(49, 53, 63, 0.8);
            color: rgba(255, 255, 255, 0.7); 
        }

    </style>
    <script>
    function showAddAssetModal() {
        alert("เปิดหน้าเพิ่มเหรียญที่ขาด!"); 
        window.location.href = "add_asset.php";
    }
</script>
</head>
<body style="background-color:#31353F; margin:0;">
<div class="container-fluid p-0">
    <div class="row full-height">   
        <!-- Sidebar -->
        <div class="col-2 text-white d-flex flex-column align-items-center " style="background-color:#1B2028">
    <div class="container text-center d-flex flex-column h-100">
        <div class="col  flex-grow-1 w-100">
            <div class="col " style="font-size:20px"><img src="img/logo.png" style="width:60px;" class="p-2">CoinTracker </div>
            <div class="col  p-2 d-flex" style="border-radius:15px;background-color:#7DAA44">
                <div class="logo-left"><img src="img/dash.svg" style="width:30px;" class="p-1"></div>
                <div class="logoname p-1" style="font-size:15px">Dash Board</div>
            </div>
            <!-- <div class="col bg-warning p-2">Portfolio</div>
            <div class="col bg-primary p-2">Add</div>
            <div class="col bg-success p-2">User</div> -->
        </div>
        <div class="col  w-100 mt-auto d-flex flex-column mt-auto">
            <a href="logout.php" class="btn  mt-auto">Logout<img src="img/log.svg" style="width:25px;" class="p-1" ></a>
        </div>
    </div>
</div>


        <!-- Main Content -->
        <div class="col-10 text-white p-3">
            <div class="user-info">
                <span>👤 <?php echo htmlspecialchars($fullname); ?> (ID: <?php echo $user_id; ?>)</span>
            </div>

            <div class="container-fluid text-center p-1 h-100">
                <div class="row gap-2 mt-3 h-25 mb-3" >
                    <div class="col text-white p-3" style="border-radius:20px;background-color:#1B2028">
                    <div class="row g-0">
                        <div class="col-5  text-white ">
                            <div class="  text-start"><img src="img/bitcoin.svg.png" style="width:60px;" ></div>
                            <div  style="padding-top:40px;font-size:20px">
                                3,000,000 ฿
                            </div>
                        </div>
                        <div class="col-7  text-white ">
                            <div class=" text-start">
                                <div class="upper"><h4>Bitcoin</h4> </div>
                                <div class="lower">BTC</div>
                            </div>
                            <div class=" p-2"><img src="img/up.svg" style="width:60px;" ></div>
                        </div>
                    </div>

                        </div>
                    <div div class="col  text-white p-3" style="border-radius:20px;background-color:#1B2028">
                        <div class="row g-0">
                        <div class="col-5  text-white ">
                            <div class="  text-start"><img src="img/eth.svg" style="width:60px;" ></div>
                            <div  style="padding-top:40px;font-size:20px">
                                80,000 ฿
                            </div>
                        </div>
                        <div class="col-7  text-white ">
                            <div class=" text-start">
                                <div class="upper"><h4>Ethereum</h4> </div>
                                <div class="lower">ETH</div>
                            </div>
                            <div class=" p-2"><img src="img/down.svg" style="width:60px;" ></div>
                        </div>
                    </div>
                </div>
                <div class="col text-white p-3" style="border-radius:20px;background-color:#1B2028">
                    <div class="row g-0">
                        <div class="col-5  text-white ">
                            <div class="  text-start"><img src="img/doge.png" style="width:60px;" ></div>
                            <div  style="padding-top:40px;font-size:20px">
                                7 ฿
                            </div>
                        </div>
                        <div class="col-7  text-white ">
                            <div class=" text-start">
                                <div class="upper"><h4>Doge Coin</h4> </div>
                                <div class="lower">DOGE</div>
                            </div>
                            <div class=" p-2"><img src="img/up.svg" style="width:60px;" ></div>
                        </div>
                    </div>

                        </div>
                </div>

                <!-- Portfolio & Assets -->
                <div class="row g-0 mt-3 h-75">
                    <!-- Portfolio Name -->
                    <div class="col-12 text-dark">
                        <div class="portfolio p-3 text-center" style="background-color:#1B2028; border-radius:20px; height: 100%;">
                        <div class="d-flex align-items-center">
                            <h2 class="mb-0 p-1" style="color:white; text-align:left;">
                                <?php echo htmlspecialchars($portfolioname); ?>
                            </h2>
                            <div class="but edit p-2" style="border-radius:50%;" data-bs-toggle="modal" data-bs-target="#editPortfolioModal" data-portfolio-id="<?php echo $portfolio_id; ?>">
                                <img src="./img/edit.svg" alt="">
                            </div>


                        </div>

                        <div class="container text-center">
                        <div class="row row-cols-2 g-3">
                            <?php if (!empty($assets)): ?>
                                <?php foreach ($assets as $asset): ?>
                                    <div class="col-12  p-3 mb-3" style="background-color: #2A2E38; border-radius: 10px;">
                                        <div class="row">
                                            <div class="col-1 text-white p-1" style="height:60%">
                                                <!-- ตรวจสอบภาพ ถ้าไม่มีจะใช้ภาพ default -->
                                                <img src="img/<?php echo !empty($asset['image_url']) ? htmlspecialchars($asset['image_url']) : 'default.png'; ?>" 
                                                    alt="<?php echo htmlspecialchars($asset['asset_name']); ?>" 
                                                    class="img-fluid mt-2" 
                                                    style="width: 50px; height: 50px;">
                                            </div>
                                            <div class="col-8 text-white text-start "> 
                                                <h5 style="color: white;"><?php echo htmlspecialchars($asset['asset_name']); ?></h5>
                                                <h7 style="color: grey;"><?php echo "Balance: " . number_format($asset['quantity'], 5) . " " . htmlspecialchars($asset['short_name']); ?></h7><br>
                                                <h7 style="color: grey;"><?php echo "Total: " . number_format($asset['quantity'] * $asset['price_live'], 2) . " Bath"; ?></h7>
                                            </div>
                                            <div class="col text-white">
                                                <div class="row g-0 mt-3 h-25">
                                                    <!-- insert -->
                                                    <div class="col text-white p-3 d-flex justify-content-center">
                                                        <div class="but add p-1" style="border-radius:50%" data-bs-toggle="modal" data-bs-target="#addAssetModal<?php echo $asset['asset_id']; ?>">
                                                            <img src="./img/add.svg" alt="">
                                                        </div>
                                                    </div>

                                                    <!-- edit -->
                                                    <div class="col text-white p-3">
                                                        <div class="but edit p-1" style="border-radius:50%" data-bs-toggle="modal" data-bs-target="#editAssetModal<?php echo $asset['asset_id']; ?>">
                                                            <img src="./img/edit.svg" alt="">
                                                        </div>
                                                    </div>

                                                    <!-- delete-->
                                                    <div class="col text-white p-3">
                                                        <div class="but delete p-1" style="border-radius:50%" data-bs-toggle="modal" data-bs-target="#deleteAssetModal<?php echo $asset['asset_id']; ?>">
                                                            <img src="./img/remove.svg" alt="Delete">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php include 'modal.php'; ?>

               
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p style="color:white;">No assets data!</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                            </div>
                        </div>
                            <!-- check coins -->
                                <?php
                                $requiredAssets = 3;
                                $currentAssetCount = count($assets);
                                $requiredAssetTypes = ["Bitcoin", "Ethereum", "DOGE"];
                                $existingAssets = array_column($assets, 'asset_name');
                                $missingAssets = array_diff($requiredAssetTypes, $existingAssets);
                                ?>

                                <?php if ($currentAssetCount < $requiredAssets): ?>
                                    <?php require 'add_asset.php'; ?>
                                    


                                    <div class="text-center mt-3">
                                    
                                    <button class="btn text-white" data-bs-toggle="modal" data-bs-target="#addCoinModal" style="background-color:#2A2E38;">
                                        <img src="./img/add.svg" alt="" width="20"> Add More
                                    </button>


                                    </div>
                                <?php endif; ?>
                                

                        </div>
                    </div>
                    
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div> 
</div>

</body>
</html>
