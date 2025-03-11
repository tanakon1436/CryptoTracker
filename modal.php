 <!-- Modal แก้ไข -->
 <div class="modal fade" id="editAssetModal<?php echo $asset['asset_id']; ?>" tabindex="-1" aria-labelledby="editAssetModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content" style="background-color: #31353F; color: white;">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editAssetModalLabel">Edit Asset: <?php echo htmlspecialchars($asset['asset_name']); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- ฟอร์มแก้ไข -->
                                <form action="update_asset.php" method="POST">
                                    <input type="hidden" name="asset_id" value="<?php echo $asset['asset_id']; ?>">
                                    <div class="form-group">
                                        <label for="quantity">Quantity</label>
                                        <input type="number" class="form-control" name="quantity" id="quantity" step="0.00001" value="<?php echo $asset['quantity']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="purchase_price">Purchase Price</label>
                                        <input type="number" name="purchase_price" class="form-control" value="<?php echo $asset['purchase_price']; ?>" required>
                                    </div>
                                    <div class="p-3">
                                      
                                    </div>
                                    <button type="submit" class="btn btn-success ">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal ลบ -->
               <!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteAssetModal<?php echo $asset['asset_id']; ?>" tabindex="-1" aria-labelledby="deleteAssetModalLabel<?php echo $asset['asset_id']; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #31353F; color: white;">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAssetModalLabel<?php echo $asset['asset_id']; ?>">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong><?php echo htmlspecialchars($asset['asset_name']); ?></strong> from your portfolio?</p>
            </div>
            <div class="modal-footer">
                <form action="delete_asset.php" method="POST">
                    <input type="hidden" name="portfolio_id" value="<?php echo $portfolio_id; ?>">
                    <input type="hidden" name="asset_id" value="<?php echo $asset['asset_id']; ?>">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal เพิ่ม Asset -->

<div class="modal fade" id="addAssetModal<?php echo $asset['asset_id']; ?>" tabindex="-1" aria-labelledby="addAssetModalLabel" aria-hidden="true" style="bg-color:#31353F">
    <div class="modal-dialog">
      <div class="modal-content" style="background-color: #31353F; color: white;">
            <div class="modal-header">
                <h5 class="modal-title" id="addAssetModalLabel">Add Asset: <?php echo htmlspecialchars($asset['asset_name']); ?></h5>
                <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- ฟอร์มเพิ่มจำนวน -->
                <form action="insert_asset.php" method="POST">
                    <input type="hidden" name="asset_id" value="<?php echo $asset['asset_id']; ?>">
                    <input type="hidden" name="current_quantity" value="<?php echo $asset['quantity']; ?>">

                    <div class="form-group">
                        <label for="quantity">Add Quantity</label>
                        <input type="number" class="form-control" name="quantity" id="quantity" step="0.00001" required>
                    </div>
                    <div class="form-group">
                        <label for="purchase_price">Purchase Price</label>
                        <input type="number" name="purchase_price" class="form-control" id="purchase_price" required>
                    </div>
                    <div class="p-3"></div>
                    <button type="submit" class="btn btn-success">Add Asset</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Coin Modal -->
<div class="modal fade" id="addCoinModal" tabindex="-1" aria-labelledby="addCoinModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #31353F; color: white;">
            <div class="modal-header">
                <h5 class="modal-title" id="addCoinModalLabel">Add More Coin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Select a coin to add:</p>
                <form action="add_asset.php" method="POST">
                    <div class="list-group">
                        <?php
                        // เชื่อมต่อฐานข้อมูลและดึงเหรียญที่ขาด
                        $conn = new mysqli("localhost", "root", "", "Crypto");
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // ดึง portfolio_id ของผู้ใช้จาก session
                        $user_id = $_SESSION['user_id'];
                        $query = "SELECT portfolio_id FROM portfolios WHERE user_id = ?";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $portfolio_id = null;

                        if ($result->num_rows > 0) {
                            // ดึง portfolio_id ตัวแรก
                            $portfolio = $result->fetch_assoc();
                            $portfolio_id = $portfolio['portfolio_id'];
                        } else {
                            echo "ไม่พบ portfolio สำหรับผู้ใช้นี้";
                            exit();
                        }

                        // ดึงรายการเหรียญทั้งหมดจาก crypto
                        $query = "SELECT * FROM crypto";
                        $result = $conn->query($query);

                        // ดึงรายการเหรียญที่ยังไม่ได้เพิ่มใน portfolio
                        $missingAssets = [];
                        while ($row = $result->fetch_assoc()) {
                            // ตรวจสอบว่าเหรียญนี้ยังไม่ได้อยู่ใน portfolio ของผู้ใช้
                            $asset_query = "SELECT * FROM assets WHERE portfolio_id = ? AND asset_id = ?";
                            $stmt = $conn->prepare($asset_query);
                            $stmt->bind_param("ii", $portfolio_id, $row['asset_id']);
                            $stmt->execute();
                            $asset_result = $stmt->get_result();

                            if ($asset_result->num_rows == 0) {
                                $missingAssets[] = $row;
                            }
                        }

                        // แสดงเหรียญที่ขาด
                        foreach ($missingAssets as $missingCoin): ?>
                        <div class="p-1">
                          <button type="submit" name="asset_id" value="<?php echo $missingCoin['asset_id']; ?>" 
                                class="list-group-item list-group-item-action btn-hover-effect" style="border-radius: 10px;">
                                <?php echo htmlspecialchars($missingCoin['asset_name']); ?>
                            </button>
                        </div>
                            
                        <?php endforeach; ?>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for editing portfolio name -->
<div class="modal fade" id="editPortfolioModal" tabindex="-1" aria-labelledby="editPortfolioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #31353F; color: white;">
            <div class="modal-header">
                <h5 class="modal-title" id="editPortfolioModalLabel">Edit Portfolio Name</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- ดึงข้อมูล  จากฐานข้อมูล -->
                <?php
                session_start();
                $connection = mysqli_connect('localhost', 'root', '', 'Crypto');
                if (!$connection) {
                    die("🔴 Database Connection Failed: " . mysqli_connect_error());
                }

                if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['portfolio_id'])) {
                    $user_id = $_SESSION['user_id']; // ดึง user_id ของผู้ใช้งานที่ล็อกอิน
                    $portfolio_id = $_GET['portfolio_id'];  // ดึง portfolio_id จาก URL

                    // ตรวจสอบว่า portfolio_id เป็นของ user นี้หรือไม่
                    $query = "SELECT portname FROM portfolios WHERE user_id = ? AND portfolio_id = ?";
                    $stmt = $connection->prepare($query);
                    $stmt->bind_param("ii", $user_id, $portfolio_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // ดึงชื่อ portfolio เก่า
                    $portfolio = $result->fetch_assoc();
                    $old_ = $portfolio['portname'];  // กำหนดค่า $old_ ให้เป็นชื่อ portfolio ที่ดึงมา

                    $stmt->close();
                }
                mysqli_close($connection);
                ?>
                <form action="edit_portfolio.php" method="POST">
                    <div class="mb-3">
                        <label for="" class="form-label">New Portfolio Name</label>
                        <!-- แสดงชื่อเก่าที่ได้จากฐานข้อมูล -->
                        <input type="text" class="form-control" id="" name="portname" value="<?php echo htmlspecialchars($old_); ?>" required>
                    </div>
                    <input type="hidden" name="portfolio_id" value="<?php echo $portfolio_id; ?>"> <!-- ส่ง portfolio_id ไปด้วย -->
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
