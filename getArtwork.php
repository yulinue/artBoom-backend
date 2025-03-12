<?php
include "database.php";
global $conn;

$artworkId = $_GET['workId'];

// Запрос данных
$sql = "SELECT works.id AS workId, 
               works.title,
               users.name AS author_name,
               works.image, 
               works.user_id AS author_id,
               users.avatar AS author_image,
               works.description,
               works.created_at,
               COUNT(DISTINCT favourites.id) AS favourites,
               COUNT(DISTINCT comments.id) AS comments,
               COUNT(DISTINCT likes.id) AS likes,
               COUNT(DISTINCT subscribers.follower_id) AS followers
        FROM works 
        JOIN users ON works.user_id = users.id
        LEFT JOIN favourites ON works.id = favourites.work_id
        LEFT JOIN comments ON works.id = comments.work_id
        LEFT JOIN likes ON works.id = likes.work_id
        LEFT JOIN subscribers ON works.user_id = subscribers.following_id
        WHERE works.id = ?
        GROUP BY works.id, works.title, users.name, works.image, works.user_id, users.avatar, works.description, works.created_at";
$stmt = $conn->prepare($sql);
$stmt->execute([$artworkId]);
$artwork_data = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT categories.name, works_categories.category_id
        FROM categories 
        JOIN works_categories ON categories.id = works_categories.category_id
        WHERE works_categories.work_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$artworkId]);
$categories_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT comments.id, comments.text, comments.created_at, users.name as user_name, users.avatar as user_avatar, users.id as user_id
        FROM comments
        JOIN users ON comments.user_id = users.id
        WHERE work_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$artworkId]);
$comments_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Запрос данных о лайках
$sql = "SELECT likes.id, likes.user_id, likes.work_id
        FROM likes
        WHERE likes.work_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$artworkId]);
$likes_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT favourites.id, favourites.user_id, favourites.work_id
        FROM favourites
        WHERE favourites.work_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$artworkId]);
$favourites_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Запрос данных о подписках
$sql = "SELECT subscribers.id, subscribers.follower_id, subscribers.following_id
        FROM subscribers
        WHERE subscribers.following_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$artwork_data['author_id']]);
$subscribers_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$response = [
    'artwork_data' => $artwork_data,
    'categories_data' => $categories_data,
    'comments_data' => $comments_data,
    'likes_data' => $likes_data,
    'favourites_data' => $favourites_data,
    'subscribers_data' => $subscribers_data
];

// Верните данные в формате JSON
header('Content-Type: application/json');
echo json_encode($response);