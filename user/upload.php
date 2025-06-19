<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
  header("Location: ../auth/login.php");
  exit;
}
require_once("../config/db.php");

// Handle form submission
if (isset($_POST['submit'])) {
  $user_id = $_SESSION['user_id'];
  $note = $_POST['note'];
  $address = $_POST['delivery_address'];
  $time = $_POST['delivery_time'];

  $stmt = $conn->prepare("INSERT INTO prescriptions (user_id, note, delivery_address, delivery_time) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("isss", $user_id, $note, $address, $time);

  if ($stmt->execute()) {
    $prescription_id = $stmt->insert_id;

    $uploadDir = "../uploads/";
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    $total = count($_FILES['images']['name']);

    if ($total > 5) {
      echo "<div class='error'>Error: Maximum 5 images allowed.</div>";
      exit;
    }

    for ($i = 0; $i < $total; $i++) {
      $tmp = $_FILES['images']['tmp_name'][$i];
      $original = $_FILES['images']['name'][$i];
      $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));

      if (in_array($ext, $allowedTypes)) {
        $newName = uniqid("presc_", true) . "." . $ext;
        $path = $uploadDir . $newName;

        if (move_uploaded_file($tmp, $path)) {
          $stmtImg = $conn->prepare("INSERT INTO prescription_images (prescription_id, image_path) VALUES (?, ?)");
          $stmtImg->bind_param("is", $prescription_id, $newName);
          $stmtImg->execute();
        }
      }
    }

    // Redirect after success
    header("Location: dashboard.php?upload=success");
    exit;
  } else {
    echo "<div class='error'>Error: Could not save prescription.</div>";
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Upload Prescription</title>
  <link rel="stylesheet" href="../assets/css/main.css">
</head>

<body>
  <div style="margin-top: 20px;">
    <a href="dashboard.php" class="primary-btn">Back</a>
  </div>

  <div class="container">
    <div class="box">
      <h2>Upload Prescription</h2>

      <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label>Note:</label>
          <textarea name="note" required></textarea>
        </div>

        <div class="form-group">
          <label>Delivery Address:</label>
          <input type="text" name="delivery_address" required>
        </div>

        <div class="form-group">
          <label>Delivery Time Slot:</label>
          <select name="delivery_time" required>
            <option value="">Select a Time Slot</option>
            <option value="8AM–10AM">8AM–10AM</option>
            <option value="10AM–12PM">10AM–12PM</option>
            <option value="2PM–4PM">2PM–4PM</option>
            <option value="4PM–6PM">4PM–6PM</option>
          </select>
        </div>

        <div class="form-group">
          <label>Upload Prescription Images (up to 5):</label>
          <input type="file" name="images[]" multiple accept="image/*" required>
        </div>

        <button type="submit" name="submit" class="primary-btn">Upload</button>
      </form>
    </div>

  </div>


  <script src="../assets/js/user/upload_prescription.js"></script>
</body>

</html>