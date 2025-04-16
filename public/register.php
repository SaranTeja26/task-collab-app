<?php
session_start();
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // secure hash

    // Check if user already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        $error = "Email already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $password])) {
            header("Location: index.php?registered=1");
        } else {
            $error = "Something went wrong!";
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex">
    <div class="bg-gray-100 flex items-center justify-center min-h-screen">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>
            <?php if (isset($error)): ?>
                <p class="text-red-600 mb-4"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <form method="POST" class="space-y-4">
                <input type="text" name="name" placeholder="Name" required class="w-full px-4 py-2 border rounded">
                <input type="email" name="email" placeholder="Email" required class="w-full px-4 py-2 border rounded">
                <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-2 border rounded">
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Register</button>
            </form>
            <p class="mt-4 text-center">Already have an account? <a href="index.php" class="text-blue-500 hover:underline">Login</a></p>
        </div>
    </div>
</body>
</html>

<?php include '../includes/footer.php'; ?>

