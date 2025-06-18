<?php
session_start();
if (isset($_SESSION['user_id'])) {
  if ($_SESSION['role'] === 'user') {
    header("Location: user/dashboard.php");
  } elseif ($_SESSION['role'] === 'pharmacy') {
    header("Location: pharmacy/dashboard.php");
  }
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Medical Prescription System</title>
  <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
  <div class="landing-wrapper">
    <header class="hero-header">
      <nav class="nav-bar">
        <div class="logo">MediSys</div>
        <div class="nav-links">
          <a href="#">Home</a>
          <a href="#">About</a>
          <a href="#">Contact</a>
          <a href="auth/login.php" class="nav-btn">Sign in</a> <!-- Updated -->
        </div>
      </nav>
      <div class="hero-content">
        <div class="hero-text">
          <h1>Prescription<br><span>Management System</span></h1>
          <p>Securely upload your prescriptions, get quotations from pharmacies, and manage your medical needs online.</p>
          <a href="auth/login.php" class="cta-button">Get started!</a> <!-- Updated -->
        </div>
        <div class="hero-image">
          <img src="assets/images/landing.png" alt="Prescription System Illustration">
        </div>
      </div>
    </header>
  </div>
</body>
</html>
