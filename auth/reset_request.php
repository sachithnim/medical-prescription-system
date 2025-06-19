<?php
require_once("../config/db.php");
require_once("../config/mailer.php"); 
$email = $_POST['email'];
$role = $_POST['role'];

// Step 1: Find user
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND role = ?");
$stmt->bind_param("ss", $email, $role);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
  $stmt->bind_result($user_id);
  $stmt->fetch();

  // Token + expiry
  $token = bin2hex(random_bytes(32));
  $expires = date("Y-m-d H:i:s", time() + 3600);

  // Clear previous reset tokens
  $conn->query("DELETE FROM password_resets WHERE user_id = $user_id");

  // Save new token
  $stmt2 = $conn->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
  $stmt2->bind_param("iss", $user_id, $token, $expires);
  $stmt2->execute();

  // Send email
  $baseUrl = "http://localhost/medical-prescription-system";
  $link = "$baseUrl/auth/reset_password.php?token=$token";
  $subject = "Reset Your Password";
  $body = "
    <h2>Password Reset Request</h2>
    <p>Click below to reset your password:</p>
    <a href='$link'>$link</a>
    <p>This link expires in 1 hour.</p>
  ";

  sendMail($email, $subject, $body); // Calling helper

  echo "Reset link sent to $email.";

} else {
  echo "No user found with that email and role.";
}
