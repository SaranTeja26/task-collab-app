<?php
session_start();
require_once "../config/db.php";

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "Unauthorized access.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the task ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo "Invalid task ID.";
    exit();
}

$taskId = (int) $_GET['id'];

// First, check if the task belongs to the logged-in user
$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
$stmt->execute([$taskId, $user_id]);
$task = $stmt->fetch();

if (!$task) {
    http_response_code(403);
    echo "Unauthorized access or task not found.";
    exit();
}

// Proceed to delete the task
$stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
if ($stmt->execute([$taskId])) {
    // Redirect back to dashboard after successful deletion
    header("Location: dashboard.php?deleted=1");
    exit();
} else {
    http_response_code(500);
    echo "Failed to delete task.";
}
?>
