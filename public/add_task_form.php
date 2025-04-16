<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Task</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Add New Task</h2>
        <form action="add_task.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Title</label>
                <input type="text" name="title" required class="mt-1 block w-full p-2 border border-gray-300 rounded">
            </div>
            <div>
                <label class="block text-sm font-medium">Deadline</label>
                <input type="datetime-local" name="deadline" required class="mt-1 block w-full p-2 border border-gray-300 rounded">
            </div>
            <div>
                <label class="block text-sm font-medium">Priority</label>
                <select name="priority" required class="mt-1 block w-full p-2 border border-gray-300 rounded">
                    <option value="">-- Select Priority --</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>
            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add Task</button>
                <a href="dashboard.php" class="ml-4 text-sm text-blue-500 hover:underline">Back to Dashboard</a>
            </div>

             <!-- 🔐 Hidden input that stores the task ID -->
            <input type="hidden" name="id" value="<?= $task['id'] ?>">

        </form>
    </div>
</body>
</html>
