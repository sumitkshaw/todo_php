<?php
require 'db.php';

// Extract task ID from URL (e.g., /tasks/3)
$uri = explode('/', $_SERVER['REQUEST_URI']);
$taskId = end($uri);

if (!is_numeric($taskId)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid task ID"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
    $stmt->execute(['id' => $taskId]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["message" => "Task deleted successfully"]);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Task not found"]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to delete task", "details" => $e->getMessage()]);
}
?>
