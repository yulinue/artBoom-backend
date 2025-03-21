<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST, OPTIONS"); // Разрешить только POST и OPTIONS запросы

include "database.php";
global $conn;

$userId = $_POST['userId'];
$workId = $_POST['workId'];
$action = $_POST['action'];

if ($action === 'add') {
    $stmt = $conn->prepare("SELECT * FROM favourites WHERE user_id = :userId AND work_id = :workId");
    $stmt->execute(['userId' => $userId, 'workId' => $workId]);
    $existingFavourite = $stmt->fetch();
    if (!$existingFavourite) {
        $stmt = $conn->prepare("INSERT INTO favourites (user_id, work_id) VALUES (:userId, :workId)");
        $stmt->execute(['userId' => $userId, 'workId' => $workId]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Работа уже добавлена в избранное']);
    }
} elseif ($action === 'remove') {
    $stmt = $conn->prepare("DELETE FROM favourites WHERE user_id = :userId AND work_id = :workId");
    $stmt->execute(['userId' => $userId, 'workId' => $workId]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Неверное действие']);
}