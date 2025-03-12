<?php
include "database.php";
global $conn;

$userId = $_GET['userId'];

// Запрос данных
$sql = "SELECT favourites.user_id, favourites.work_id, works.title, works.image, users.name as userName, users.avatar
        FROM favourites 
        JOIN works ON favourites.work_id = works.id
        JOIN users ON works.user_id = users.id
        WHERE favourites.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$userId]);
$data = $stmt->fetchAll(2);

// Верните данные в формате JSON
header('Content-Type: application/json');
echo json_encode($data);