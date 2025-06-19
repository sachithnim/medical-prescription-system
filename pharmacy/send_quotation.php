<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pharmacy') {
    header("Location: ../auth/login.php");
    exit;
}

require_once("../config/db.php");
require_once("../config/mailer.php");

$pid = $_GET['prescription_id'] ?? null;
if (!$pid) die("No prescription selected.");

// Get user email
$stmt = $conn->prepare("SELECT u.email FROM prescriptions p JOIN users u ON p.user_id = u.id WHERE p.id = ?");
$stmt->bind_param("i", $pid);
$stmt->execute();
$stmt->bind_result($user_email);
$stmt->fetch();
$stmt->close();

// Fetch prescription images
$images = $conn->query("SELECT image_path FROM prescription_images WHERE prescription_id = $pid");

// Fetch drug prices
$drugListResult = $conn->query("SELECT drug_name, unit_price FROM drug_prices");
$drugPrices = [];
while ($d = $drugListResult->fetch_assoc()) {
    $drugPrices[$d['drug_name']] = $d['unit_price'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['drugs_json'])) {
    $drugs = json_decode($_POST['drugs_json'], true);
    if (count($drugs) === 0) {
        echo "<p>No drugs added.</p>";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO quotations (prescription_id) VALUES (?)");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $quotation_id = $stmt->insert_id;

    $grand_total = 0;

    foreach ($drugs as $drug) {
        $name = $drug['drug'];
        $qty = (int)$drug['qty'];

        $stmt_price = $conn->prepare("SELECT unit_price FROM drug_prices WHERE drug_name = ?");
        $stmt_price->bind_param("s", $name);
        $stmt_price->execute();
        $stmt_price->bind_result($unit_price);
        $stmt_price->fetch();
        $stmt_price->close();

        if ($unit_price === null) continue;

        $total = $qty * $unit_price;
        $grand_total += $total;

        $stmt = $conn->prepare("INSERT INTO quotation_items (quotation_id, drug_name, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isidd", $quotation_id, $name, $qty, $unit_price, $total);
        $stmt->execute();
    }

    $subject = "New Quotation Available";
    $body = "Your quotation (Rs. " . number_format($grand_total, 2) . ") is ready. Please log in to review.";
    sendMail($user_email, $subject, $body);

    header("Location: dashboard.php?quotation=success");
    exit;

    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Send Quotation</title>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>

<body>
    <div style="margin-top: 20px;">
        <a href="dashboard.php" class="primary-btn">Back</a>
    </div>
    <div class="container">
        <h2>Send Quotation</h2>
        <div class="quotation-container">
            <div class="prescription-section">
                <div class="box">
                    <h3>Prescription Images</h3>
                    <?php
                    $imageFiles = [];
                    $images->data_seek(0);
                    while ($img = $images->fetch_assoc()) {
                        $imageFiles[] = $img['image_path'];
                    }
                    if (!empty($imageFiles)): ?>
                        <img id="main-image" src="../uploads/<?= htmlspecialchars($imageFiles[0]) ?>" alt="Prescription" class="main-image">
                        <div class="thumbnail-container">
                            <?php foreach ($imageFiles as $index => $imagePath): ?>
                                <img src="../uploads/<?= htmlspecialchars($imagePath) ?>" alt="Img <?= $index + 1 ?>" class="thumbnail" onclick="changeMainImage('../uploads/<?= htmlspecialchars($imagePath) ?>')">
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>No prescription images available.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="quotation-section">


                <div class="quotation-box">
                    <div class="quotation-header">
                        <span><strong>Drug</strong></span>
                        <span><strong>Quantity</strong></span>
                        <span><strong>Amount</strong></span>
                    </div>
                    <div id="quotation-items"></div>

                    <div class="quotation-total">
                        <span>Total</span>
                        <strong id="grand-total">0.00</strong>
                    </div>


                </div>

                <div class="add-drug-wrapper">
                    <div class="input-row">
                        <label for="drug">Drug</label>
                        <input list="drug-options" id="drug" placeholder="Search and select drug..." required>
                    </div>

                    <div class="input-row">
                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" required>
                    </div>

                    <div class="form-group">
                        <button type="button" class="pharmacy" id="add-drug">Add</button>
                    </div>
                </div>


                <datalist id="drug-options">
                    <?php foreach ($drugPrices as $name => $price): ?>
                        <option value="<?= htmlspecialchars($name) ?>">
                        <?php endforeach; ?>
                </datalist>
                <hr class="form-divider">
                <div class="send-btn-wrapper">
                    <form method="POST" onsubmit="return validateBeforeSubmit();">
                        <input type="hidden" name="drugs_json" id="drugs_json">
                        <button type="submit" class="pharmacy">Send Quotation</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        window.drugPrices = <?= json_encode($drugPrices) ?>;
    </script>
    <script src="../assets/js/pharmacy/send_quotation.js"></script>
</body>

</html>