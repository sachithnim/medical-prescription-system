<?php
$token = $_GET['token'] ?? '';
$error = $_GET['error'] ?? null;

if ($error === 'expired') {
    echo '<div class="flash-error">Reset link has expired. Please request a new one.</div>';
} elseif ($error === 'invalid') {
    echo '<div class="flash-error">Invalid or used token. Please try again.</div>';
} elseif ($error === 'empty') {
    echo '<div class="flash-error">Please fill in all fields.</div>';
} elseif ($error === 'nomatch') {
    echo '<div class="flash-error">Passwords do not match.</div>';
}
?>


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
  <div class="auth-wrapper">
    <div class="auth-box">
      <div class="auth-left">
        <form method="POST" action="update_password.php">
          <h2>Reset Password</h2>

          <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

          <div class="form-group">
            <label>New Password</label>
            <input type="password" name="password" required>
          </div>

          <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm" required>
          </div>

          <button type="submit" class="primary-btn">Update Password</button>
        </form>
      </div>
      <div class="auth-right">
        <img src="../assets/images/reset-password.png" alt="Reset Password">
      </div>
    </div>
  </div>
</body>
</html>
