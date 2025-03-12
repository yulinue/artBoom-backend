<?php
include "./database.php";
global $conn;

$userId = $_GET['userId'];

$sql = "SELECT COUNT(works.id) as worksCount from works WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$userId]);
$works = $stmt->fetch(2);

$sql = "SELECT COUNT(subscribers.id) as followersCount from subscribers WHERE following_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$userId]);
$followers = $stmt->fetch(2);

$sql = "SELECT COUNT(subscribers.id) as subscribesCount from subscribers WHERE follower_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$userId]);
$subscribes = $stmt->fetch(2);

$result = [
    'works' => $works['worksCount'],
    'followers' => $followers['followersCount'],
    'subscribes' => $subscribes['subscribesCount']
];

// Верните данные в формате JSON
header('Content-Type: application/json');
echo json_encode($result);