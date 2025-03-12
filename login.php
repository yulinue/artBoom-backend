<?php
include "database.php";
global $conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'];
    $password = $data['password'];

    // Валидация
    $errors = [];
    if (empty($email)) {
        $errors['email'] = "Email не может быть пустым";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Неверный формат email";
    } elseif (strlen($email) > 255) {
        $errors['email'] = "Email не может быть длиннее 255 символов";
    }
    if (empty($password)) {
        $errors['password'] = "Пароль не может быть пустым";
    } elseif (strlen($password) < 6 || strlen($password) > 15) {
        $errors['password'] = "Пароль должен быть от 6 до 15 символов";
    }

    if (count($errors) > 0) {
        header('Content-Type: application/json');
        echo json_encode(['errors' => $errors]);
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'userId' => $user['id'],
                'name' => $user['name'],
                'avatar' => $user['avatar'],
                'bio' => $user['bio'],
                'created_at' => $user['created_at'],
                'role' => $user['role'],
                'cover' => $user['cover'],
                'blocked' => $user['blocked'],
                'block_reason' => $user['block_reason']
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['errors' => ['email' => 'Неверный email или пароль']]);
        }
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['errors' => ['server' => 'Неверный метод запроса']]);
}