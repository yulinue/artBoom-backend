<?php
include "database.php";
global $conn;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'];

    if ($id) {
        $sql = "DELETE FROM categories WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);

        $response = [
            'status' => 'success',
            'message' => 'Категория успешно удалена',
        ];

        echo json_encode($response);
    } else {
        $response = [
            'status' => 'error',
            'message' => 'ID категории не указан',
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