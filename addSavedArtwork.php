<?php

header("Access-Control-Allow-Origin: *"); // Разрешить всем доменам
header("Access-Control-Allow-Methods: POST, OPTIONS"); // Разрешить только POST и OPTIONS запросы
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Разрешить заголовки Content-Type и Authorization
header("Content-Type: application/json"); // Установить тип контента JSON

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Для запросов OPTIONS просто возвращаем статус 200 без тела ответа
    http_response_code(200);
    exit();
}

include "./database.php";
global $conn;

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_POST['userId'];
        $work_id = $_POST['workId'];

        $sql = "INSERT INTO `favourites`(`user_id`, `work_id`) 
                VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id, $work_id]);

        // Возвращение успешного ответа
        echo json_encode(['success' => true, 'message' => 'Комментарий добавлен']);
    } else {
        // Возвращение ошибки, если запрос не POST
        echo json_encode(['success' => false, 'message' => 'Неверный метод запроса']);
    }
} catch (Exception $e) {
    // Логирование ошибки
    error_log($e->getMessage());
    // Возвращение ошибки клиенту
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>