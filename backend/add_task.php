<?php
require 'db.php';

// Get raw input & decode JSON
$data = json_decode(file_get_contents("php://input"), true);

// Validation: title must not be empty
if (!isset($data['title']) || trim($data['title']) === '') {
    http_response_code(400);
    echo json_encode(["error" => "Task title is required"]);
    exit;
}

$title = trim($data['title']);

try {
    $stmt = $pdo->prepare("INSERT INTO tasks (title) VALUES (:title)");
    $stmt->execute(['title' => $title]);

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
