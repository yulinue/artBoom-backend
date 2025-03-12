<?php

include "./database.php";
global $conn;

$sql = "SELECT * FROM categories";
$stmt = $conn->prepare($sql);
$stmt->execute();

$data = $stmt->fetchAll(2);

header('Content-Type: application/json');
echo json_encode($data);