<?php
require 'db.php';

$taskId = basename($_SERVER['REQUEST_URI']);

if (!is_numeric($taskId)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid task ID"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
    $stmt->execute(['id' => $taskId]);

    echo json_encode(
        $stmt->rowCount() > 0
            ? ["message" => "Task deleted successfully"]
            : (http_response_code(404) or ["error" => "Task not found"])
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to delete task", "details" => $e->getMessage()]);
}
?>
