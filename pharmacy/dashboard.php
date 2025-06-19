<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pharmacy') {
    header("Location: ../auth/login.php");
    exit;
}
require_once("../config/db.php");

$pharmacy_id = $_SESSION['user_id'];
$pharmacy_name = "Pharmacy"; // Default fallback

$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $pharmacy_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $full_name = trim($result->fetch_assoc()['name']);
    $pharmacy_name = explode(' ', $full_name)[0]; // Get first word only
}

// Pagination setup
$limit = 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$baseUrl = "dashboard.php";
include("../includes/pagination.php");

// Count total rows for pagination
$totalQuery = $conn->query("SELECT COUNT(*) AS total FROM quotations");
$totalRows = $totalQuery->fetch_assoc()['total'];

// Get paginated data
$sql = "SELECT q.id AS quotation_id, q.prescription_id, q.status, q.sent_at, p.note, u.name 
        FROM quotations q
        JOIN prescriptions p ON q.prescription_id = p.id
        JOIN users u ON p.user_id = u.id
        ORDER BY q.sent_at DESC
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Pharmacy Dashboard</title>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>

<body>
    <div class="dashboard-header">
        <h1 class="dashboard-title">Welcome, <?= htmlspecialchars($pharmacy_name) ?>!</h1>

        <a href="../auth/logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="container box">
        <div class="dashboard-top">
            <div class="btn-group">
                <a href="view_prescriptions.php" class="primary-btn">View New Prescriptions</a>

            </div>
        </div>

        <h2 class="dashboard-subtitle">Quotation History</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="table-wrapper">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Note</th>
                            <th>Status</th>
                            <th>Sent At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#QT-<?= str_pad($row['quotation_id'], 4, "0", STR_PAD_LEFT) ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['note']) ?></td>
                                <td>
                                    <?php if ($row['status'] === 'accepted') : ?>
                                        <span class="success">Accepted</span>
                                    <?php elseif ($row['status'] === 'rejected') : ?>
                                        <span class="error">Rejected</span>
                                    <?php elseif ($row['status'] === 'pending') : ?>
                                        <span class="warning">Pending</span>
                                    <?php else : ?>
                                        <span class="neutral">Unknown</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $row['sent_at'] ?></td>
                                <td>
                                    <a href="view_quotation.php?id=<?= $row['quotation_id'] ?>" class="primary-btn">View</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php renderPagination($totalRows, $limit, $page, $baseUrl); ?>
            </div>
        <?php else: ?>
            <p>No quotations submitted yet.</p>
        <?php endif; ?>
    </div>
</body>

</html>