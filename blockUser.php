<?php
include "database.php";
global $conn;

header('Content-Type: application/json');

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['userId']) && isset($data['reason'])) {
        $userId = intval($data['userId']);
        $reason = $data['reason'];

        // Валидация
        if (empty($reason)) {
            $response['success'] = false;
            $response['message'] = 'Причина блокировки не может быть пустой.';
            echo json_encode($response);
            exit;
        } elseif (strlen($reason) > 255) {
            $response['success'] = false;
            $response['message'] = 'Причина блокировки не может быть длиннее 255 символов.';
            echo json_encode($response);
            exit;
        }

        $sql = "UPDATE users SET blocked = 1, block_reason = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$reason, $userId]);

        $response['success'] = true;
        $response['message'] = 'Пользователь заблокирован';
    } else {
        $response['success'] = false;
        $response['message'] = 'Недостаточно данных для блокировки пользователя.';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Неверный метод запроса.';
}

echo json_encode($response);
?>