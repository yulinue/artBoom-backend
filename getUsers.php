<?php
include "database.php";
global $conn;

// Запрос данных
$sql = "SELECT * FROM users WHERE role != 'admin'";
$stmt = $conn->prepare($sql);
$stmt->execute();

$data = $stmt->fetchAll(2);

// Верните данные в формате JSON
header('Content-Type: application/json');
echo json_encode($data);