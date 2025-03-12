<?php
include "database.php";
global $conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $userId = $data['userId'];
    $newPassword = $data['newPassword'];

    // Валидация
    $errors = [];

    if (empty($newPassword)) {
        $errors['newPassword'] = "Новый пароль не может быть пустым";
    } elseif (strlen($newPassword) < 6) {
        $errors['newPassword'] = "Новый пароль должен быть не менее 6 символов";
    }

    if (count($errors) > 0) {
        // Возвращаем ошибки
        header('Content-Type: application/json');
        echo json_encode(['errors' => $errors]);
        exit;
    }

    // Получаем текущего пользователя по userId
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $errors['server'] = "Пользователь не найден";
    } elseif (password_verify($newPassword, $user['password'])) {
        $errors['newPassword'] = "Новый пароль не может совпадать с текущим паролем";
    }

    if (count($errors) > 0) {
        // Возвращаем ошибки
        header('Content-Type: application/json');
        echo json_encode(['errors' => $errors]);
        exit;
    }

    // Хешируем новый пароль
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Обновляем пароль в базе данных
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$hashedNewPassword, $userId]);

    // Возвращаем успешный ответ
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} else {
    // Если метод запроса не POST, возвращаем ошибку
    header('Content-Type: application/json');
    echo json_encode(['errors' => ['server' => 'Неверный метод запроса']]);
}