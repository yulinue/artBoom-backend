<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET, OPTIONS"); // Разрешить только GET и OPTIONS запросы

include "database.php";
global $conn;

$userId = $_GET['userId'];

try {
    $stmt = $conn->prepare("
        SELECT u.id, u.name, u.avatar
        FROM subscribers s
        JOIN users u ON s.following_id = u.id
        WHERE s.follower_id = :userId
    ");
    $stmt->execute(['userId' => $userId]);
    $subscribes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['subscribes' => $subscribes]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>