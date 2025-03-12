<?php
include "database.php";
global $conn;

// Получение случайного artwork
$sql = "SELECT works.id as workId, users.id, users.blocked
        FROM works 
        JOIN users ON works.user_id = users.id
        WHERE !users.blocked
        ORDER BY RAND() LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute();

$result = $stmt->fetch(2);

if ($result) {
    echo json_encode($result);
} else {
    echo json_encode(['error' => 'Ошибка при получении данных']);
}