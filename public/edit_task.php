<?php
session_start();
require_once "../config/db.php";

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    exit("Unauthorized");
}

// Check if the task ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    exit("Invalid task ID.");
}

$taskId = (int) $_GET['id'];

// Fetch the task from the database
$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
$stmt->execute([$taskId, $_SESSION['user_id']]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    exit("Task not found.");
}

// Process form submission (updating task)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $deadline = trim($_POST['deadline'] ?? '');
    $priority = trim($_POST['priority'] ?? '');

    // Validate input fields
    if (!$title || !$deadline || !$priority) {
        exit("All fields are required!");
    }

    $valid_priorities = ['high', 'medium', 'low'];
    if (!in_array($priority, $valid_priorities)) {
        exit("Invalid priority value.");
    }

    // Sanitize inputs
    $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $deadline = htmlspecialchars($deadline, ENT_QUOTES, 'UTF-8');
    $priority = htmlspecialchars($priority, ENT_QUOTES, 'UTF-8');

    // Update the task in the database
    $stmt = $conn->prepare("UPDATE tasks SET title = ?, deadline = ?, priority = ? WHERE id = ? AND user_id = ?");
    if ($stmt->execute([$title, $deadline, $priority, $taskId, $_SESSION['user_id']])) {
        // Redirect to the dashboard after success
        header("Location: dashboard.php");
        exit(); // Make sure no further code is executed
    } else {
        exit("Failed to update task.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
    <div class="w-full max-w-xl bg-white rounded-2xl shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Edit Task</h2>

        <form method="POST" action="edit_task.php?id=<?= $taskId ?>" class="space-y-5">
            <input type="hidden" name="id" value="<?= $task['id'] ?>">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input 
                    type="text" 
                    name="title" 
                    value="<?= htmlspecialchars($task['title']) ?>" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" 
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                <input 
                    type="date" 
                    name="deadline" 
                    value="<?= $task['deadline'] ?>" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" 
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                <select 
                    name="priority" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" 
                    required>
                    <option value="high" <?= $task['priority'] == 'high' ? 'selected' : '' ?>>High</option>
                    <option value="medium" <?= $task['priority'] == 'medium' ? 'selected' : '' ?>>Medium</option>
                    <option value="low" <?= $task['priority'] == 'low' ? 'selected' : '' ?>>Low</option>
                </select>
            </div>

            <div class="flex justify-between items-center pt-4">
                <a href="dashboard.php" class="text-gray-500 hover:text-gray-700 transition">Cancel</a>
                <button 
                    type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg transition">
                    Update Task
                </button>
            </div>
        </form>
    </div>
</body>
</html>
