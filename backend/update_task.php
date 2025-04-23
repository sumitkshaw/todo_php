<?php
require 'db.php';

$taskId = basename($_SERVER['REQUEST_URI']);
$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['title']) && !isset($data['is_completed'])) {
    http_response_code(400);
    echo json_encode(["error" => "Nothing to update"]);
    exit;
}

try {
    $fields = [];
    $params = ['id' => $taskId];

    if (!empty($data['title'])) {
        $fields[] = "title = :title";
        $params['title'] = trim($data['title']);
    }

    if (isset($data['is_completed'])) {
        $fields[] = "is_completed = :is_completed";
        $params['is_completed'] = $data['is_completed'] ? 1 : 0;
    }

    $query = "UPDATE tasks SET " . implode(', ', $fields) . " WHERE id = :id";
    $pdo->prepare($query)->execute($params);

    echo json_encode(["message" => "Task updated successfully"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to update task", "details" => $e->getMessage()]);
}
?>
