<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pharmacy') {
    header("Location: ../auth/login.php");
    exit;
}

require_once("../config/db.php");

$sql = "SELECT p.*, u.name 
        FROM prescriptions p
        JOIN users u ON p.user_id = u.id
        WHERE p.id NOT IN (SELECT prescription_id FROM quotations)";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>New Prescriptions</title>
  <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
  <div style="margin-top: 20px;">
      <a href="dashboard.php" class="primary-btn">Back</a>
    </div>
  <div class="container box">
    <div class="dashboard-top">
      <h1 class="dashboard-title">New Prescriptions to Quote</h1>
    </div>
    <?php if ($result->num_rows > 0): ?>
      <div class="table-wrapper">
        <table class="dashboard-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>User</th>
              <th>Note</th>
              <th>Delivery Info</th>
              <th>Delivery Time</th>
              <th>Images</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()): 
              $pid = $row['id'];
              $images = $conn->query("SELECT image_path FROM prescription_images WHERE prescription_id = $pid");
              $thumbs = [];
              while ($img = $images->fetch_assoc()) {
                $thumbs[] = $img['image_path'];
              }
            ?>
              <tr>
                <td>#<?= str_pad($pid, 4, "0", STR_PAD_LEFT) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['note']) ?></td>
                <td><?= htmlspecialchars($row['delivery_address']) ?></td>
                <td><?= htmlspecialchars($row['delivery_time']) ?></td>
                <td>
                  <div class="thumbnail-preview">
                    <?php foreach (array_slice($thumbs, 0, 3) as $img): ?>
                      <img src="../uploads/<?= htmlspecialchars($img) ?>" alt="Preview">
                    <?php endforeach; ?>
                    <?php if (count($thumbs) > 3): ?>
                      +<?= count($thumbs) - 3 ?> more
                    <?php endif; ?>
                  </div>
                </td>
                <td>
                  <a href="send_quotation.php?prescription_id=<?= $pid ?>" class="primary-btn">Prepare</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p>No new prescriptions to quote.</p>
    <?php endif; ?>
  </div>

  <script src="../assets/js/pharmacy/view_prescriptions.js"></script>
</body>
</html>
