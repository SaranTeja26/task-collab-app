<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY deadline ASC");
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '../includes/header.php'; ?>
<!-- Page-specific content -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4">Welcome, <?= $_SESSION['name'] ?> 👋</h2>

        <div class="mb-6">
            <h3 class="text-xl font-semibold mb-2">Your Tasks</h3>
            <div class="flex space-x-4 mb-4">
                <div>
                    <label for="filter-status" class="block text-sm font-medium">Status:</label>
                    <select id="filter-status" onchange="filterTasks()" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div>
                    <label for="filter-priority" class="block text-sm font-medium">Priority:</label>
                    <select id="filter-priority" onchange="filterTasks()" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                        <option value="">All</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
            </div>
        </div>

        <div id="task-list" class="mb-6">
            <!-- Tasks will load here dynamically -->
        </div>

        <div class="mb-6">
        <a href="add_task_form.php" class="text-blue-600 hover:underline mr-4">+ Add Task</a>
        <a href="logout.php" class="text-red-600 hover:underline">Logout</a>
        </div>

        <hr class="my-4">

        <?php if (count($tasks) > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100 text-left text-sm font-semibold">
                            <th class="p-3 border-b">Title</th>
                            <th class="p-3 border-b">Deadline</th>
                            <th class="p-3 border-b">Priority</th>
                            <th class="p-3 border-b">Status</th>
                            <th class="p-3 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border-b"><?= htmlspecialchars($task['title']) ?></td>
                            <td class="p-3 border-b"><?= $task['deadline'] ?></td>
                            <td class="p-3 border-b"><?= ucfirst($task['priority']) ?></td>
                            <td class="p-3 border-b"><?= ucfirst($task['status']) ?></td>
                            <td class="p-3 border-b">
                                <a href="edit_task.php?id=<?= $task['id'] ?>" class="text-blue-600 hover:underline mr-2">Edit</a>
                                <a href="delete_task.php?id=<?= $task['id'] ?>" onclick="return confirm('Delete this task?')" class="text-red-600 hover:underline">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-gray-600">No tasks found. <a href="add_task_form.php" class="text-blue-500 hover:underline">Create one!</a></p>
        <?php endif; ?>
    </div>

    <script src="../assets/script.js"></script>
</body>
</html>
<?php include '../includes/footer.php'; ?>
