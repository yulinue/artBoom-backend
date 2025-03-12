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
        $user_id = $_POST['user_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $image = $_FILES['image']['name'];
        $categories = json_decode($_POST['categories']);
        $fileExtension = pathinfo($image, PATHINFO_EXTENSION);

        // Валидация типа файла
        $allowedExtensions = ['png', 'jpg', 'jpeg', 'webp'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            throw new Exception("Недопустимый тип файла. Разрешены только файлы с расширениями: " . implode(', ', $allowedExtensions));
        }

        $target_dir = "../frontend/public/artWorks/";
        $file_name = uniqid() . '.' . $fileExtension;
        $target_file = $target_dir . $file_name;
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            throw new Exception("Ошибка при загрузке файла");
        }
        $relative_path = '../../../public/artWorks/' . $file_name;
        $sql = "INSERT INTO `works`(`user_id`, `title`, `description`, `image`) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id, $title, $description, $relative_path]);
        $work_id = $conn->lastInsertId();
        foreach ($categories as $category_id) {
            $sql = "INSERT INTO `works_categories`(`work_id`, `category_id`) 
                    VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$work_id, $category_id]);
        }
        echo json_encode(['success' => true, 'message' => 'Работа успешно добавлена']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Неверный метод запроса']);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>