<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Get all users
$stmt = $conn->prepare("SELECT id, name, email, role FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get all tasks
$taskStmt = $conn->prepare("SELECT t.id, t.title, t.deadline, t.priority, t.status, u.name as user_name, u.id as user_id 
                            FROM tasks t 
                            JOIN users u ON t.user_id = u.id 
                            ORDER BY t.deadline ASC");
$taskStmt->execute();
$tasks = $taskStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Function to handle task deletion
        function deleteTask(taskId) {
            if (confirm("Are you sure you want to delete this task?")) {
                fetch('delete_task.php?id=' + taskId)
                    .then(res => res.text())
                    .then(data => {
                        alert(data);
                        location.reload(); // Reload page after deletion
                    })
                    .catch(error => {
                        alert("Error deleting task");
                        console.error(error);
                    });
            }
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen text-gray-800">

    <!-- Header -->
    <header class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-blue-700">Admin Dashboard</h1>
        <div class="flex items-center space-x-4">
            <span class="text-gray-700 font-medium">Welcome, <span class="font-semibold"><?= htmlspecialchars($_SESSION['name']) ?></span></span>
            <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white font-medium px-4 py-2 rounded transition duration-200">Logout</a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-8 max-w-7xl mx-auto">
        <!-- Users Table -->
        <section class="mb-10">
            <h2 class="text-2xl font-semibold mb-4 text-gray-700 border-b pb-2">Registered Users</h2>
            <div class="overflow-x-auto shadow rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">ID</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Role</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4"><?= $user['id'] ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($user['name']) ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($user['email']) ?></td>
                                <td class="px-6 py-4 capitalize"><?= htmlspecialchars($user['role']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Tasks Table -->
        <section>
            <h2 class="text-2xl font-semibold mb-4 text-gray-700 border-b pb-2">All Tasks</h2>

            <?php if (empty($tasks)): ?>
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded">
                    No tasks found.
                </div>
            <?php else: ?>
                <div class="overflow-x-auto shadow rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 bg-white">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">User</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Title</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Deadline</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Priority</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($tasks as $task): ?>
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="px-6 py-4 text-sm"><?= htmlspecialchars($task['user_name']) ?></td>
                                    <td class="px-6 py-4 text-sm font-medium"><?= htmlspecialchars($task['title']) ?></td>
                                    <td class="px-6 py-4 text-sm"><?= htmlspecialchars($task['deadline']) ?></td>
                                    <td class="px-6 py-4 text-sm capitalize"><?= htmlspecialchars($task['priority']) ?></td>
                                    <td class="px-6 py-4 text-sm capitalize"><?= htmlspecialchars($task['status']) ?></td>
                                    <td class="px-6 py-4">
                                        <button onclick="deleteTask(<?= $task['id'] ?>)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
