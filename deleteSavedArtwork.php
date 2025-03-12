<?php
include "database.php";
global $conn;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из POST-запроса
    $user_id = $_POST['userId'];
    $work_id = $_POST['workId'];

    // Проверяем, что данные переданы
    if (!isset($user_id) || !isset($work_id)) {
        $response = [
            'status' => 'error',
            'message' => 'Не переданы необходимые данные',
        ];
        echo json_encode($response);
        exit;
    }

    // Подготавливаем SQL-запрос для удаления
    $sql = "DELETE FROM favourites WHERE user_id = ? AND work_id = ?";
    $stmt = $conn->prepare($sql);

    // Выполняем запрос
    if ($stmt->execute([$user_id, $work_id])) {
        $response = [
            'status' => 'success',
            'message' => 'Работа успешно удалена из избранного',
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Ошибка при удалении работы из избранного',
        ];
    }

    echo json_encode($response);

} else {
    $response = [
        'status' => 'error',
        'message' => 'Неверный метод запроса',
    ];

    echo json_encode($response);
}