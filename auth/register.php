<?php
require_once("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if ($_POST["password"] !== $_POST["confirm_password"]) {
    $error = "Passwords do not match.";
  } else {
    $hashed_password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, address, contact_no, dob, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
      "sssssss",
      $_POST["name"],
      $_POST["email"],
      $_POST["address"],
      $_POST["contact"],
      $_POST["dob"],
      $hashed_password,
      $_POST["role"]
    );
    $stmt->execute();

    $success = "Registered successfully. <a href='login.php'>Login here</a>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="../assets/css/main.css">
</head>

<body>
  <div class="auth-wrapper">
    <div class="auth-box">

      <div class="auth-left">
        <form method="POST" class="login-form" style="width: 100%;">
          <h2>Register</h2>

          <?php
          if (isset($error)) echo "<p class='error' style='color: red;'>$error</p>";
          if (isset($success)) echo "<p class='success' style='color: green;'>$success</p>";
          ?>

          <div style="display: flex; gap: 20px; margin-bottom: 1rem;">
            <div style="flex: 1;">
              <label>Role</label>
              <select name="role" required style="width: 100%;">
                <option value="">Select Role</option>
                <option value="user">User</option>
                <option value="pharmacy">Pharmacy</option>
              </select>
            </div>
            <div style="flex: 1;">
              <label>Date of Birth</label>
              <input type="date" name="dob" required style="width: 100%;">
            </div>
          </div>

          <div style="display: flex; gap: 20px; margin-bottom: 1rem;">
            <div style="flex: 1;">
              <label>Name</label>
              <input type="text" name="name" required style="width: 100%;">
            </div>
            <div style="flex: 1;">
              <label>Email</label>
              <input type="email" name="email" required style="width: 100%;">
            </div>
          </div>

          <div style="display: flex; gap: 20px; margin-bottom: 1rem;">
            <div style="flex: 1;">
              <label>Address</label>
              <input type="text" name="address" required style="width: 100%;">
            </div>
            <div style="flex: 1;">
              <label>Contact No</label>
              <input type="text" name="contact" required style="width: 100%;">
            </div>
          </div>

          <div style="display: flex; gap: 20px; margin-bottom: 1rem;">
            <div style="flex: 1;">
              <label>Password</label>
              <input type="password" name="password" required style="width: 100%;">
            </div>
            <div style="flex: 1;">
              <label>Confirm Password</label>
              <input type="password" name="confirm_password" required style="width: 100%;">
            </div>
          </div>

          <button type="submit" class="primary-btn" style="margin-top: 10px;">Register</button>
          <p class="bottom-link" style="margin-top: 10px;">Already have an account? <a href="login.php">Login here</a></p>
        </form>

      </div>


      <div class="auth-right">
        <img src="../assets/images/register.jpg" alt="Register Illustration">
      </div>
    </div>
  </div>
  <script src="../assets/js/auth/register.js"></script>
</body>

</html>