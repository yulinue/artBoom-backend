<?php
header("Access-Control-Allow-Origin: *"); // Разрешить всем доменам
header("Access-Control-Allow-Methods: DELETE, OPTIONS"); // Разрешить только DELETE и OPTIONS запросы
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
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $workId = $_GET['workId'];

        // Удаление связей между работой и категориями из таблицы `works_categories`
        $sql = "DELETE FROM `works_categories` WHERE `work_id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$workId]);

        // Удаление работы из таблицы `works`
        $sql = "DELETE FROM `works` WHERE `id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$workId]);

        

        // Возвращение успешного ответа
        echo json_encode(['success' => true, 'message' => 'Работа успешно удалена']);
    } else {
        // Возвращение ошибки, если запрос не DELETE
        echo json_encode(['success' => false, 'message' => 'Неверный метод запроса']);
    }
} catch (Exception $e) {
    // Логирование ошибки
    error_log($e->getMessage());
    // Возвращение ошибки клиенту
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}