<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Ответ на предварительный запрос (preflight request)
    http_response_code(200);
    exit;
}

include "database.php";
global $conn;

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $userId = $_GET['userId'];
    if (!$userId) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'User ID is required']);
        exit;
    }
    try {
        $sql = "DELETE FROM favourites WHERE user_id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $sql = "DELETE FROM likes WHERE user_id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $sql = "DELETE FROM subscribers WHERE follower_id = :userId OR following_id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $sql = "DELETE FROM users WHERE id = :userId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error deleting account: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}