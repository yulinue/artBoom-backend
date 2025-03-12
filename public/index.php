<?php
// Если запрашиваемый файл существует в public — просто отдаем его
if (file_exists(__DIR__ . $_SERVER['REQUEST_URI'])) {
    return false;
}

// Если файла нет — загружаем React (index.html)
include 'index.html';
?>