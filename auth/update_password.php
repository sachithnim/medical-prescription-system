<?php
require_once("../config/db.php");
date_default_timezone_set('Asia/Colombo'); //Use your local timezone

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm'] ?? '';

// Validate fields
if (empty($token) || empty($password) || empty($confirm)) {
    header("Location: reset_password.php?error=empty");
    exit;
}

if ($password !== $confirm) {
    header("Location: reset_password.php?error=nomatch&token=" . urlencode($token));
    exit;
}

// Fetch token details
$stmt = $conn->prepare("SELECT user_id, expires_at FROM password_resets WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows !== 1) {
    header("Location: reset_password.php?error=invalid");
    exit;
}

$stmt->bind_result($user_id, $expires);
$stmt->fetch();

// Token expired?
if (strtotime($expires) < time()) {
    header("Location: reset_password.php?error=expired");
    exit;
}

// Hash password and update user
$hashed = password_hash($password, PASSWORD_DEFAULT);
$update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
$update->bind_param("si", $hashed, $user_id);
$update->execute();

// Delete token
$delete = $conn->prepare("DELETE FROM password_resets WHERE user_id = ?");
$delete->bind_param("i", $user_id);
$delete->execute();

// Redirect to login
header("Location: login.php?reset=success");
exit;
