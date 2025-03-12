<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST, OPTIONS"); // Разрешить только POST и OPTIONS запросы

include "database.php";
global $conn;

// Проверка наличия всех необходимых параметров
if (!isset($_POST['followerId']) || !isset($_POST['followingId']) || !isset($_POST['action'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Отсутствуют необходимые параметры']);
    exit;
}

$followerId = $_POST['followerId'];
$followingId = $_POST['followingId'];
$action = $_POST['action'];

try {
    if ($action === 'add') {
        // Проверяем, не подписан ли пользователь уже на автора работы
        $stmt = $conn->prepare("SELECT * FROM subscribers WHERE follower_id = :followerId AND following_id = :followingId");
        $stmt->execute(['followerId' => $followerId, 'followingId' => $followingId]);
        $existingSubscribe = $stmt->fetch();

        if (!$existingSubscribe) {
            $stmt = $conn->prepare("INSERT INTO subscribers (follower_id, following_id) VALUES (:followerId, :followingId)");
            $stmt->execute(['followerId' => $followerId, 'followingId' => $followingId]);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Пользователь уже подписан на автора']);
        }
    } elseif ($action === 'remove') {
        $stmt = $conn->prepare("DELETE FROM subscribers WHERE follower_id = :followerId AND following_id = :followingId");
        $stmt->execute(['followerId' => $followerId, 'followingId' => $followingId]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Неверное действие']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>