<?php
include "database.php";
global $conn;

header('Content-Type: application/json');

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['userId'])) {
        $userId = intval($data['userId']);

        // Обновление статуса блокировки пользователя
        $sql = "UPDATE users SET blocked = 0, block_reason = NULL WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);

        $response['success'] = true;
        $response['message'] = 'Пользователь разблокирован';
        
    }else {
        $response['success'] = false;
        $response['message'] = 'Недостаточно данных для разблокировки пользователя.';
    }
}
else {
    $response['success'] = false;
    $response['message'] = 'Неверный метод запроса.';
}

echo json_encode($response);
?>