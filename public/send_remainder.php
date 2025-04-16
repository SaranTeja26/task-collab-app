<?php
require_once "config/db.php"; // Adjust the path if needed

// Get tasks due tomorrow
$sql = "SELECT t.title, t.deadline, u.email, u.name 
        FROM tasks t 
        JOIN users u ON t.user_id = u.id 
        WHERE t.deadline = CURDATE() + INTERVAL 1 DAY AND t.status = 'pending'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Loop through and send emails
foreach ($tasks as $task) {
    $to = $task['email'];
    $subject = "⏰ Task Reminder: {$task['title']} due tomorrow!";
    $message = "Hi {$task['name']},\n\nJust a friendly reminder that your task \"{$task['title']}\" is due on {$task['deadline']}.\n\nBest,\nTask Manager App";

    $headers = "From: reminders@yourapp.com"; // Replace with a valid sender

    if (mail($to, $subject, $message, $headers)) {
        echo "Reminder sent to {$to}<br>";
    } else {
        echo "Failed to send to {$to}<br>";
    }
}
?>
