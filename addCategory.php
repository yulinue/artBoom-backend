<?php
include "database.php";
global $conn;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['cat_name'])) {
        $catName = $data['cat_name'];

        // Валидация
        if (empty($catName)) {
            $response = [
                'status' => 'error',
                'message' => 'Название категории не может быть пустым',
            ];
            echo json_encode($response);
            exit;
        } elseif (strlen($catName) > 255) {
            $response = [
                'status' => 'error',
                'message' => 'Название категории не может быть длиннее 255 символов',
            ];
            echo json_encode($response);
            exit;
        }

        $sql = "INSERT INTO categories (name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$catName]);

        $response = [
            'status' => 'success',
            'message' => 'Категория успешно добавлена',
            'cat_name' => $catName,
        ];

        echo json_encode($response);
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Название категории не указано',
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