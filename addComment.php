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
        $text = $_POST['text'];

        // Проверка на пустой комментарий
        if (empty(trim($text))) {
            echo json_encode(['success' => false, 'message' => 'Комментарий не может быть пустым']);
            exit();
        }

        // Добавление комментария в базу данных
        $sql = "INSERT INTO `comments`(`user_id`, `work_id`, `text`) 
                VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id, $work_id, $text]);

        // Получение ID нового комментария
        $comment_id = $conn->lastInsertId();

        // Получение данных о новом комментарии
        $sql = "SELECT c.id, c.text, c.created_at, u.name AS user_name, u.avatar AS user_avatar
                FROM comments c
                JOIN users u ON c.user_id = u.id
                WHERE c.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$comment_id]);
        $comment = $stmt->fetch(PDO::FETCH_ASSOC);

        // Возвращение данных о новом комментарии
        echo json_encode([
            'success' => true,
            'message' => 'Комментарий добавлен',
            'comment' => $comment
        ]);
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