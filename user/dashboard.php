<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit;
}
require_once("../config/db.php");

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, created_at, delivery_time FROM prescriptions WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$prescriptions = $stmt->get_result();


// fetch user first name
$user_stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_name = "User";

if ($user_result->num_rows > 0) {
    $user_data = $user_result->fetch_assoc();
    $user_name = htmlspecialchars($user_data['name']);
    $first_name = explode(' ', trim($user_name))[0];
} else {
    $first_name = "User";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard</title>
  <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
  <div class="dashboard-header">
        <h1 class="dashboard-title">Welcome, <?= $first_name ?>!</h1>
        <a href="../auth/logout.php" class="logout-btn">Logout</a>
    </div>
  <div class="container box">
  <div class="dashboard-top">

    <?php if (isset($_GET['upload']) && $_GET['upload'] === 'success'): ?>
      <div class="success">Prescription uploaded successfully!</div>
    <?php endif; ?>

    <div class="btn-group">
      <a href="upload.php" class="primary-btn">Upload New Prescription</a>
      <a href="view_quotation.php" class="primary-btn">View Quotations</a>
    </div>
  </div>

  <h2 class="dashboard-subtitle">Your Prescription History</h2>

  <?php if ($prescriptions->num_rows > 0): ?>
    <div class="table-wrapper">
      <table class="dashboard-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Uploaded At</th>
            <th>Delivery Time</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $prescriptions->fetch_assoc()):
            $id = $row['id'];
            $created = $row['created_at'];
            $time = $row['delivery_time'];

            $q = $conn->query("SELECT status FROM quotations WHERE prescription_id = $id LIMIT 1");
            $status = $q->fetch_assoc()['status'] ?? 'Not Quoted';
          ?>
          <tr>
            <td>#<?= str_pad($id, 4, "0", STR_PAD_LEFT) ?></td>
            <td><?= $created ?></td>
            <td><?= $time ?></td>
            <td>
              <?php if ($status === 'accepted') : ?>
                <span class="success">Accepted</span>
              <?php elseif ($status === 'rejected') : ?>
                <span class="error">Rejected</span>
              <?php elseif ($status === 'pending') : ?>
                <span class="warning">Pending</span>
              <?php else : ?>
                <span class="neutral">Not Quoted</span>
              <?php endif; ?>
            </td>
            <td><a href="view_prescription.php?id=<?= $id ?>" class="primary-btn">View</a></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
     
    </div>
  <?php else: ?>
    <p>No prescriptions uploaded yet.</p>
  <?php endif; ?>

  <script src="../assets/js/user/dashboard.js"></script>
</body>
</html>
