<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pharmacy') {
  header("Location: ../auth/login.php");
  exit;
}

require_once("../config/db.php");

if (!isset($_GET['id'])) {
  echo "Invalid quotation ID.";
  exit;
}

$quotation_id = (int) $_GET['id'];

// Get quotation and prescription info
$stmt = $conn->prepare("SELECT q.status, q.sent_at, p.note, u.name 
                        FROM quotations q
                        JOIN prescriptions p ON q.prescription_id = p.id
                        JOIN users u ON p.user_id = u.id
                        WHERE q.id = ?");
$stmt->bind_param("i", $quotation_id);
$stmt->execute();
$result = $stmt->get_result();
$quotation = $result->fetch_assoc();

if (!$quotation) {
  echo "Quotation not found.";
  exit;
}

// Get items
$items = $conn->query("SELECT * FROM quotation_items WHERE quotation_id = $quotation_id");
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>View Quotation</title>
  <link rel="stylesheet" href="../assets/css/main.css">
</head>

<body>
  <div style="margin-top: 20px;">
    <a href="dashboard.php" class="primary-btn">Back</a>
  </div>
  <div class="container">
    <div class="box">
      <h2 class="dashboard-title">Quotation #QT-<?= str_pad($quotation_id, 4, "0", STR_PAD_LEFT) ?></h2>

      <div class="quotation-summary">
        <p><strong>User:</strong> <?= htmlspecialchars($quotation['name']) ?></p>
        <p><strong>Note:</strong> <?= htmlspecialchars($quotation['note']) ?></p>
        <p><strong>Status:</strong>
          <?php
          if ($quotation['status'] === 'accepted') {
            echo "<span class='success'>Accepted</span>";
          } elseif ($quotation['status'] === 'rejected') {
            echo "<span class='error'>Rejected</span>";
          } else {
            echo "<span class='warning'>" . ucfirst($quotation['status']) . "</span>";
          }
          ?>
        </p>
        <p><strong>Sent At:</strong> <?= $quotation['sent_at'] ?></p>
      </div>

      <div class="quotation-box">
        <div class="quotation-header">
          <span>Drug</span>
          <span>Quantity</span>
          <span>Unit Price</span>
          <span>Total</span>
        </div>

        <?php $grand_total = 0;
        while ($item = $items->fetch_assoc()):
          $grand_total += $item['total_price'];
        ?>
          <div class="quotation-item">
            <span><?= htmlspecialchars($item['drug_name']) ?></span>
            <span><?= number_format($item['unit_price'], 2) ?> x <?= (int)$item['quantity'] ?></span>
            <span>Rs. <?= number_format($item['unit_price'], 2) ?></span>
            <span>Rs. <?= number_format($item['total_price'], 2) ?></span>
          </div>
        <?php endwhile; ?>

        <div class="quotation-total">
          <span>Total</span>
          <span></span>
          <span></span>
          <span><strong>Rs. <?= number_format($grand_total, 2) ?></strong></span>
        </div>
      </div>


    </div>

  </div>
</body>

</html>