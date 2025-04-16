<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_id'])) exit("Unauthorized");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $deadline = isset($_POST['deadline']) ? trim($_POST['deadline']) : '';
    $priority = isset($_POST['priority']) ? trim($_POST['priority']) : '';

    if (!$title || !$deadline || !$priority) {
        exit("All fields are required!");
    }

    if (!in_array($priority, ['high', 'medium', 'low'])) {
        exit("Invalid priority value.");
    }

    $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $deadline = htmlspecialchars($deadline, ENT_QUOTES, 'UTF-8');
    $priority = htmlspecialchars($priority, ENT_QUOTES, 'UTF-8');

    $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, deadline, priority, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->execute([$_SESSION['user_id'], $title, $deadline, $priority]);

    // Redirect back to dashboard
    header("Location: dashboard.php?success=1");
    exit;
} else {
    exit("Invalid request method.");
}
