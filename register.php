<?php
include "database.php";
global $conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $email = $data['email'];
    $username = $data['username'];
    $password = $data['password'];
    $confirmPassword = $data['confirmPassword'];

    // Валидация
    $errors = [];
    if (empty($email)) {
        $errors['email'] = "Email не может быть пустым";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Неверный формат email";
    } elseif (strlen($email) > 255) {
        $errors['email'] = "Email не может быть длиннее 255 символов";
    }
    if (empty($username)) {
        $errors['username'] = "Имя пользователя не может быть пустым";
    } elseif (strlen($username) > 255) {
        $errors['username'] = "Имя пользователя не может быть длиннее 255 символов";
    }
    if (empty($password)) {
        $errors['password'] = "Пароль не может быть пустым";
    } elseif (strlen($password) < 6 || strlen($password) > 15) {
        $errors['password'] = "Пароль должен быть от 6 до 15 символов";
    }
    if (empty($confirmPassword)) {
        $errors['confirmPassword'] = "Повтор пароля не может быть пустым";
    } elseif ($password !== $confirmPassword) {
        $errors['confirmPassword'] = "Пароли не совпадают";
    }

    if (count($errors) > 0) {
        header('Content-Type: application/json');
        echo json_encode(['errors' => $errors]);
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors['email'] = "Пользователь с таким email уже существует";
        }
        $stmt = $conn->prepare("SELECT id FROM users WHERE name = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $errors['username'] = "Пользователь с таким именем уже существует";
        }
        if (count($errors) > 0) {
            header('Content-Type: application/json');
            echo json_encode(['errors' => $errors]);
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->execute([$username, $email, $hashedPassword]);
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
        }
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['errors' => ['server' => 'Неверный метод запроса']]);
}
?>