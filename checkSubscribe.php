<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: GET, OPTIONS"); // Разрешить только GET и OPTIONS запросы

include "database.php";
global $conn;

$followerId = $_GET['followerId'];
$followingId = $_GET['followingId'];

try {
    $stmt = $conn->prepare("SELECT * FROM subscribers WHERE follower_id = :followerId AND following_id = :followingId");
    $stmt->execute(['followerId' => $followerId, 'followingId' => $followingId]);
    $isSubscribed = $stmt->fetch();

    echo json_encode(['isSubscribed' => $isSubscribed ? true : false]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}