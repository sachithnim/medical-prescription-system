<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit;
}
require_once("../config/db.php");

// Get all quotations for this user
$user_id = $_SESSION['user_id'];
$sql = "SELECT q.id AS quotation_id, q.status, q.sent_at
        FROM quotations q
        JOIN prescriptions p ON q.prescription_id = p.id
        WHERE p.user_id = ?
        ORDER BY q.sent_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$quotations = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Quotations</title>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
  <div style="margin-top: 20px;">
      <a href="dashboard.php" class="primary-btn">Back</a>
    </div>
<div class="container">
    <h2>Your Quotations</h2>

    <?php while ($q = $quotations->fetch_assoc()) {
        $qid = $q['quotation_id'];

        // Fetch quotation_items for this quotation
        $items = $conn->query("SELECT * FROM quotation_items WHERE quotation_id = $qid");
    ?>
      <div class="box">
        <strong>Quotation:</strong> #QT-<?= str_pad($qid, 4, "0", STR_PAD_LEFT) ?><br>


        <table>
          <thead>
            <tr>
              <th>Drug</th>
              <th>Quantity</th>
              <th>Unit Price</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $grand_total = 0;
            while ($item = $items->fetch_assoc()) {
                $grand_total += $item['total_price'];
                echo "<tr>
                        <td>" . htmlspecialchars($item['drug_name']) . "</td>
                        <td>" . (int)$item['quantity'] . "</td>
                        <td>Rs. " . number_format((float)$item['unit_price'], 2) . "</td>
                        <td>Rs. " . number_format((float)$item['total_price'], 2) . "</td>
                      </tr>";
            }
            ?>
            <tr>
              <td colspan="3"><strong>Total</strong></td>
              <td><strong>Rs. <?= number_format($grand_total, 2) ?></strong></td>
            </tr>
          </tbody>
        </table>

        <p><strong>Status:</strong> <?= ucfirst($q['status']) ?></p>
        <p><strong>Sent At:</strong> <?= $q['sent_at'] ?></p>

        <?php if ($q['status'] === 'pending') { ?>
          <form method="POST" action="handle_response.php">
            <input type="hidden" name="quotation_id" value="<?= $qid ?>">
            <button name="action" value="accept">Accept</button>
            <button name="action" value="reject">Reject</button>
          </form>
        <?php } ?>
      </div>
    <?php } ?>
    
</div>
<script src="../assets/js/user/view_quotation.js"></script>

</body>
</html>
