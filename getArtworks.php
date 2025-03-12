<?php
include "database.php";
global $conn;

// Запрос данных
$sql = "SELECT works.title, users.name AS author_name, works.image, works.user_id AS author_id, users.avatar AS author_image, works.id AS workId,
        GROUP_CONCAT(categories.id) AS category_ids, GROUP_CONCAT(categories.name) AS category_names, users.blocked as author_blocked
        FROM works 
        JOIN users ON works.user_id = users.id
        LEFT JOIN works_categories ON works.id = works_categories.work_id
        LEFT JOIN categories ON works_categories.category_id = categories.id
        WHERE users.blocked != 1
        GROUP BY works.id";
$stmt = $conn->prepare($sql);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($data as &$artwork) {
    $category_ids = $artwork['category_ids'] ?? '';
    $category_names = $artwork['category_names'] ?? '';

    $category_ids_array = explode(',', $category_ids);
    $category_names_array = explode(',', $category_names);

    $artwork['categories'] = array_map(function($id, $name) {
        return ['id' => (int)$id, 'name' => $name];
    }, $category_ids_array, $category_names_array);
    unset($artwork['category_ids'], $artwork['category_names']);
}
header('Content-Type: application/json');
echo json_encode($data);
?>