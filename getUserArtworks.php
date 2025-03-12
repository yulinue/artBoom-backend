<?php
include "./database.php";
global $conn;

$userId = $_GET['userId'];

$sql = "SELECT works.id as workId, works.image as workImage, users.avatar as userAvatar,
        users.name as userName, works.title as workTitle
        FROM works 
        JOIN users on works.user_id = users.id
        WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$userId]);

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($data);