<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit;
}
require_once("../config/db.php");

if (!isset($_GET['id'])) {
    echo "Invalid Request";
    exit;
}

$id = (int) $_GET['id'];
$user_id = $_SESSION['user_id'];

// Get prescription details
$stmt = $conn->prepare("SELECT * FROM prescriptions WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$prescription = $stmt->get_result()->fetch_assoc();

if (!$prescription) {
    echo "Prescription not found.";
    exit;
}

// Get images
$images = $conn->query("SELECT image_path FROM prescription_images WHERE prescription_id = $id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Prescription</title>
  <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
  <div style="margin-top: 20px;">
      <a href="dashboard.php" class="primary-btn">Back</a>
    </div>
  <div class="container box">
    <h2>Prescription #<?= str_pad($id, 4, "0", STR_PAD_LEFT) ?></h2>
    
    <p><strong>Note:</strong> <?= htmlspecialchars($prescription['note']) ?></p>
    <p><strong>Delivery Address:</strong> <?= htmlspecialchars($prescription['delivery_address']) ?></p>
    <p><strong>Delivery Time:</strong> <?= htmlspecialchars($prescription['delivery_time']) ?></p>
    <p><strong>Uploaded At:</strong> <?= $prescription['created_at'] ?></p>

    <h4>Images:</h4>
    <div class="prescription-images">
      <?php while ($img = $images->fetch_assoc()): ?>
        <img src="../uploads/<?= $img['image_path'] ?>" class="prescription">
      <?php endwhile; ?>
    </div>
  </div>
</body>
</html>
