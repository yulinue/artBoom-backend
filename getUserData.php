<?php
include "./database.php";
global $conn;

$userId = $_GET['userId'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$userId]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);
header('Content-Type: application/json');
echo json_encode($data);