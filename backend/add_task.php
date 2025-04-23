<?php
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$title = trim($data['title'] ?? '');

if ($title === '') {
    http_response_code(400);
    echo json_encode(["error" => "Task title is required"]);
    exit;
}

try {
    $pdo->prepare("INSERT INTO tasks (title) VALUES (:title)")
        ->execute(['title' => $title]);

    echo json_encode([
        "message" => "Task added successfully",
        "task" => [
            "id" => $pdo->lastInsertId(),
            "title" => $title,
            "is_completed" => 0
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to add task", "details" => $e->getMessage()]);
}
?>
