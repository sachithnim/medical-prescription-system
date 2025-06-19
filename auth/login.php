<?php
session_start();
require_once("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $role = $_POST["role"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ? AND role = ?");
  $stmt->bind_param("ss", $email, $role);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows === 1) {
    $stmt->bind_result($id, $hashed);
    $stmt->fetch();

    if (password_verify($password, $hashed)) {
      $_SESSION["user_id"] = $id;
      $_SESSION["role"] = $role;

      // Remember Me func set cookie for 7 days
      if (isset($_POST["remember"])) {
        setcookie("email", $email, time() + (86400 * 7), "/");
        setcookie("role", $role, time() + (86400 * 7), "/");
      } else {
        setcookie("email", "", time() - 3600, "/");
        setcookie("role", "", time() - 3600, "/");
      }

      if ($role === "user") {
        header("Location: ../user/dashboard.php");
      } elseif ($role === "pharmacy") {
        header("Location: ../pharmacy/dashboard.php");
      }
      exit;
    }
  }

  $error = "Invalid email or password.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="../assets/css/main.css">
</head>

<body>
  <div class="auth-wrapper">
    <div class="auth-box">
      <div class="auth-left">
        <form method="POST" class="login-form">
          <?php if (isset($_GET['reset']) && $_GET['reset'] === 'success'): ?>
            <div class="flash-success">Your password has been updated successfully. Please log in.</div>
          <?php endif; ?>

          <h2>Login</h2>

          <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

          <div class="form-group">
            <label>Role</label>
            <select name="role" required>
              <option value="">Select Role</option>
              <option value="user">User</option>
              <option value="pharmacy">Pharmacy</option>
            </select>
          </div>

          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>
          </div>

          <div class="form-group">
            <label>
              Password
              <a href="forgot_password.php" class="forgot-link">Forgot password?</a>
            </label>
            <input type="password" name="password" placeholder="Enter your password" required>
          </div>

          <div class="form-group checkbox">
            <label><input type="checkbox" name="remember"> Remember me</label>
          </div>

          <button type="submit" class="primary-btn">Login</button>
          <p class="bottom-link">Don’t have an account? <a href="register.php">Sign up</a></p>
        </form>
      </div>

      <div class="auth-right">
        <img src="../assets/images/login.jpg" alt="Login Illustration">
      </div>
    </div>
  </div>
</body>

</html>