<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_id'])) exit("Unauthorized");

$status = $_GET['status'] ?? '';
$priority = $_GET['priority'] ?? '';

$query = "SELECT * FROM tasks WHERE user_id = ?";
$params = [$_SESSION['user_id']];

if ($status) {
    $query .= " AND status = ?";
    $params[] = $status;
}

if ($priority) {
    $query .= " AND priority = ?";
    $params[] = $priority;
}

$query .= " ORDER BY deadline ASC";
$stmt = $conn->prepare($query);
$stmt->execute($params);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($tasks as $task) {
    echo "<div class='border p-4 rounded shadow mb-2'>
            <strong>" . htmlspecialchars($task['title']) . "</strong><br>
            Deadline: " . $task['deadline'] . "<br>
            Priority: " . ucfirst($task['priority']) . "<br>
            Status: " . ucfirst($task['status']) . "<br>
            <button onclick='deleteTask(" . $task['id'] . ")'>Delete</button>
          </div>";
}
?>
