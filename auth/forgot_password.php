<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
  <div class="auth-wrapper">
    <div class="auth-box">
      <div class="auth-left">
        <form method="POST" action="reset_request.php">
          <h2>Forgot Password</h2>
          <p>Enter your role and email to receive a password reset link.</p>

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
            <input type="email" name="email" required>
          </div>

          <button type="submit" class="primary-btn">Send Reset Link</button>
        </form>
      </div>
      <div class="auth-right">
        <img src="../assets/images/forgot-password.jpg" alt="Forgot Password">
      </div>
    </div>
  </div>
</body>
</html>
