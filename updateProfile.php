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
        $userId = $_POST['userId'];
        $name = $_POST['name'];
        $bio = $_POST['bio'];

        // Валидация на стороне сервера
        if (empty($name)) {
            throw new Exception("Имя пользователя не может быть пустым");
        }

        // Проверка на уникальность имени
        $sql = "SELECT COUNT(*) FROM users WHERE name = ? AND id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $userId]);
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            throw new Exception("Это имя уже занято");
        }

        // Отладочное сообщение для проверки данных
        error_log("Received userId: $userId, name: $name, bio: $bio");

        // Обновление данных пользователя
        $sql = "UPDATE users SET name = ?, bio = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $bio, $userId]);

        // Отладочное сообщение для проверки успешности обновления
        error_log("User data updated successfully for userId: $userId");

        // Обработка загрузки аватара
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['avatar']['name'];
            $fileExtension = pathinfo($image, PATHINFO_EXTENSION);
            $allowedExtensions = ['png', 'jpg', 'jpeg', 'webp'];

            if (!in_array($fileExtension, $allowedExtensions)) {
                throw new Exception("Недопустимый тип файла для аватара. Разрешены только файлы с расширениями: " . implode(', ', $allowedExtensions));
            }

            $target_dir = "../frontend/public/users/";
            $file_name = uniqid() . '.' . $fileExtension;
            $target_file = $target_dir . $file_name;
            if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
                throw new Exception("Ошибка при загрузке файла");
            }
            $avatarPath = '../../../public/users/' . $file_name;
            $sql = "UPDATE users SET avatar = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$avatarPath, $userId]);
            error_log("Avatar uploaded successfully to: $avatarPath");
        }

        // Обработка загрузки обложки
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['cover']['name'];
            $fileExtension = pathinfo($image, PATHINFO_EXTENSION);
            $allowedExtensions = ['png', 'jpg', 'jpeg', 'webp'];

            if (!in_array($fileExtension, $allowedExtensions)) {
                throw new Exception("Недопустимый тип файла для обложки. Разрешены только файлы с расширениями: " . implode(', ', $allowedExtensions));
            }

            $target_dir = "../frontend/public/covers/";
            $file_name = uniqid() . '.' . $fileExtension;
            $target_file = $target_dir . $file_name;
            if (!move_uploaded_file($_FILES["cover"]["tmp_name"], $target_file)) {
                throw new Exception("Ошибка при загрузке файла");
            }
            $coverPath = '../../../public/covers/' . $file_name;
            $sql = "UPDATE users SET cover = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$coverPath, $userId]);
            error_log("Cover uploaded successfully to: $coverPath");
        }

        // Получение обновленных данных пользователя
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Отладочное сообщение для проверки полученных данных пользователя
        error_log("Retrieved user data: " . json_encode($user));

        // Возвращение успешного ответа
        echo json_encode([
            'success' => true,
            'message' => 'Профиль успешно обновлен',
            'user' => $user
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
?>