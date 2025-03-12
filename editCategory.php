<?php
include "database.php";
global $conn;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id']) && isset($data['name'])) {
        $id = $data['id'];
        $name = $data['name'];

        // Валидация
        if (empty($name)) {
            $response = [
                'status' => 'error',
                'message' => 'Название категории не может быть пустым',
            ];
            echo json_encode($response);
            exit;
        } elseif (strlen($name) > 255) {
            $response = [
                'status' => 'error',
                'message' => 'Название категории не может быть длиннее 255 символов',
            ];
            echo json_encode($response);
            exit;
        }

        $sql = "UPDATE categories SET name = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $id]);

        $response = [
            'status' => 'success',
            'message' => 'Категория успешно отредактирована',
        ];

        echo json_encode($response);
    } else {
        $response = [
            'status' => 'error',
            'message' => 'ID или название категории не указаны',
        ];

        echo json_encode($response);
    }
} else {
    $response = [
        'status' => 'error',
        'message' => 'Неверный метод запроса',
    ];

    echo json_encode($response);
}
?>