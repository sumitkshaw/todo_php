<?php
require 'db.php';

try {
    $tasks = $pdo->query("SELECT * FROM tasks ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($tasks);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch tasks", "details" => $e->getMessage()]);
}
?>
