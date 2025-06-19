<?php
// Default limit and page
if (!isset($limit)) $limit = 5;
if (!isset($page)) $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if (!isset($baseUrl)) $baseUrl = strtok($_SERVER["REQUEST_URI"], '?');

// SQL OFFSET
$offset = ($page - 1) * $limit;

// Pagination render function
function renderPagination($totalRows, $limit, $page, $baseUrl) {
    $totalPages = ceil($totalRows / $limit);
    if ($totalPages <= 1) return;

    echo '<div class="pagination">';
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = ($i == $page) ? 'active-page' : '';
        echo "<a class='page-link $active' href='{$baseUrl}?page=$i'>$i</a>";
    }
    echo '</div>';
}
?>
