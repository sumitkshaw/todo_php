<?php
require 'db.php';

// Extract task ID from URL (e.g., /tasks/3)
$uri = explode('/', $_SERVER['REQUEST_URI']);
$taskId = end($uri);

// Get raw input & decode JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['title']) && !isset($data['is_completed'])) {
    http_response_code(400);
    echo json_encode(["error" => "Nothing to update"]);
    exit;
}

try {
    $fields = [];
    $params = [];

    if (isset($data['title'])) {
        $fields[] = "title = :title";
        $params['title'] = trim($data['title']);
    }

    if (isset($data['is_completed'])) {
        $fields[] = "is_completed = :is_completed";
        $params['is_completed'] = $data['is_completed'] ? 1 : 0;
    }

    $params['id'] = $taskId;
    $query = "UPDATE tasks SET " . implode(', ', $fields) . " WHERE id = :id";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    echo json_encode(["message" => "Task updated successfully"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to update task", "details" => $e->getMessage()]);
}
?>
