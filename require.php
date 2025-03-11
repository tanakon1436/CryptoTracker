<?php
// กำหนดจำนวนเหรียญที่ต้องมี
$requiredAssets = 3;

// นับจำนวนสินทรัพย์ที่มีอยู่
$currentAssetCount = count($assets);

// รายการเหรียญที่ควรมี
$requiredAssetTypes = ["Bitcoin", "Ethereum", "BNB"];

// หาว่าเหรียญไหนที่ขาด
$existingAssets = array_column($assets, 'asset_name');
$missingAssets = array_diff($requiredAssetTypes, $existingAssets);
?>

<!-- แสดงปุ่ม Add Asset ถ้ายังมีไม่ครบ 3 เหรียญ -->
<?php if ($currentAssetCount < $requiredAssets): ?>
    <div class="text-center mt-3">
        <button class="btn btn-warning" onclick="showAddAssetModal()">➕ Add Missing Asset</button>
    </div>

    <!-- แสดงเหรียญที่ขาด -->
    <div class="text-center mt-2 text-white">
        <p>Missing: <?php echo implode(", ", $missingAssets); ?></p>
    </div>
<?php endif; ?>
