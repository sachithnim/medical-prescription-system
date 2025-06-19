<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit;
}
require_once("../config/db.php");
require_once("../config/mailer.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quotation_id'], $_POST['action'])) {
    $qid = $_POST['quotation_id'];
    $action = $_POST['action'];

    // Update status
    if (in_array($action, ['accept', 'reject'])) {
        $new_status = $action === 'accept' ? 'accepted' : 'rejected';
        $stmt = $conn->prepare("UPDATE quotations SET status=? WHERE id=?");
        $stmt->bind_param("si", $new_status, $qid);
        $stmt->execute();

        // Notify pharmacy
        // Get pharmacy email from prescription → user → role='pharmacy'
        $q = $conn->query("SELECT u.email FROM quotations q
                          JOIN prescriptions p ON q.prescription_id = p.id
                          JOIN users u ON u.role = 'pharmacy'
                          WHERE q.id = $qid LIMIT 1");
        $pharmacy = $q->fetch_assoc();
        if ($pharmacy) {
            $subject = "User has $new_status your quotation";
            $body = "The user has $new_status the quotation you sent. Please log in to view details.";
            sendMail($pharmacy['email'], $subject, $body);
        }

        header("Location: view_quotation.php?response=$new_status");
        exit;
    }
}
