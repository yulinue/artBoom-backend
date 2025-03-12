-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 20 2024 г., 13:53
-- Версия сервера: 10.7.5-MariaDB-log
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `artBoom`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Портреты'),
(2, 'Реализм'),
(3, 'Скетчи'),
(4, 'Аниме и Манга'),
(5, 'Комиксы'),
(6, 'Животные'),
(7, 'Пейзаж'),
(8, 'Абстракция'),
(9, 'Фанарт'),
(10, 'Концепт Арт'),
(11, 'Фентези'),
(12, 'Пиксель арт');

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `work_id` int(10) NOT NULL,
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `work_id`, `text`, `created_at`) VALUES
(1, 2, 6, 'Вау, вот это пушка!!!', '2024-11-14 20:53:16'),
(6, 4, 8, 'sfvsfvs', '2024-11-19 14:51:51');

-- --------------------------------------------------------

--
-- Структура таблицы `favourites`
--

CREATE TABLE `favourites` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `work_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `favourites`
--

INSERT INTO `favourites` (`id`, `user_id`, `work_id`) VALUES
(11, 3, 8),
(13, 3, 11),
(14, 3, 9),
(15, 3, 10),
(17, 1, 13),
(18, 3, 12),
(23, 1, 9),
(26, 1, 10),
(29, 4, 8),
(30, 4, 6),
(31, 4, 12);

-- --------------------------------------------------------

--
-- Структура таблицы `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `work_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `work_id`) VALUES
(2, 3, 6),
(9, 3, 8),
(7, 3, 10),
(8, 3, 11),
(10, 3, 13),
(22, 4, 6),
(13, 4, 8),
(12, 4, 9),
(11, 4, 11);

-- --------------------------------------------------------

--
-- Структура таблицы `subscribers`
--

CREATE TABLE `subscribers` (
  `id` int(10) NOT NULL,
  `follower_id` int(10) NOT NULL,
  `following_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `subscribers`
--

INSERT INTO `subscribers` (`id`, `follower_id`, `following_id`) VALUES
(23, 3, 4),
(24, 3, 2),
(32, 4, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blocked` tinyint(1) NOT NULL DEFAULT 0,
  `block_reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `bio`, `avatar`, `role`, `created_at`, `cover`, `blocked`, `block_reason`) VALUES
(1, '1@mail.com', '$2y$10$kdY6/bkRW2pkH3UTlxjQAeZ3Bvd1SGsJ2vPhrBShKNxhtQN3m/QRa', 'test', 'описание', '../../../public/users/673ba28e90187.png', 'user', '2024-11-07 13:32:07', '../../../public/covers/673ba28e91357.png', 1, 'нарушение правил'),
(2, 'user@mail.ru', '$2y$10$bW4oJe6ufQ.WPAMJGekm7.ptky61/K50cdKcH4bCZGrrSdSpsaIJO', 'first user', NULL, NULL, 'user', '2024-11-14 14:46:06', '', 0, NULL),
(3, 'user2@mail.ru', '$2y$10$0iY0qoAdzzS6v4lZuqqLte7ZO8WKyakVbMZCw10ywa5iCH4FNevy2', 'крутой пользователь', 'подписывайтесь, друзья!', '../../../public/users/673b0a55ed595.jpeg', 'user', '2024-11-14 15:00:43', '../../../public/covers/673b0a55ee8a2.png', 0, NULL),
(4, 'test@gmail.com', '$2y$10$A853wKCQBn0Q.BuwDKd3qeUCAYYvj5Qz8n1O95hhcUh3jYAGEHgP.', 'kame_nm', 'всем привет дуслар', '../../../public/users/673ca5afcc109.jpeg', 'user', '2024-11-18 09:24:51', '../../../public/covers/673ca6837a42c.png', 0, NULL),
(5, 'admin@gmail.com', '$2y$10$5/hh1qzK3AJalaadEMt9CuuPuFUOhdDQ/mMtvLGxAtcT2/JPm2iRK', 'admin', NULL, NULL, 'admin', '2024-11-18 19:35:42', NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `works`
--

CREATE TABLE `works` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `works`
--

INSERT INTO `works` (`id`, `user_id`, `title`, `description`, `image`, `created_at`) VALUES
(6, 3, 'крутая работа', 'очень крутая работа', '../../../public/artWorks/673643886f76a.jpeg', '2024-11-14 18:38:00'),
(8, 4, 'Глубины океана', 'Мой первый пейзаж', '../../../public/artWorks/673b0f5b3044a.png', '2024-11-18 09:56:43'),
(9, 4, 'Океанариум', 'амваикиукжцдж', '../../../public/artWorks/673b0f8fdfb33.jpeg', '2024-11-18 09:57:35'),
(10, 4, 'Подводная пещера', 'лказхцзу2 цлуьалц 2ьль', '../../../public/artWorks/673b10f64ba13.jpeg', '2024-11-18 10:03:34'),
(11, 2, 'Взрыв', 'ьсльв цулцту ытвлстыв', '../../../public/artWorks/673b115f3b046.jpeg', '2024-11-18 10:05:19'),
(12, 2, 'Водоросли', 'алытв уткалтцкшар цоушаоц', '../../../public/artWorks/673b11dfc25d1.jpeg', '2024-11-18 10:07:27'),
(13, 2, 'Концепт Арт', 'ву в уьвльу', '../../../public/artWorks/673b12177d388.jpeg', '2024-11-18 10:08:23'),
(14, 4, 'Пейзаж', 'уаьлцка цтулацту цу ', '../../../public/artWorks/673b126882f52.png', '2024-11-18 10:09:44'),
(15, 3, 'Подводный мир', 'амам влацлу отвмоытв', '../../../public/artWorks/673b12b409b3c.jpeg', '2024-11-18 10:11:00'),
(16, 1, 'test', 'ddddddd', '../../../public/artWorks/673b960acf690.png', '2024-11-18 19:31:22');

-- --------------------------------------------------------

--
-- Структура таблицы `works_categories`
--

CREATE TABLE `works_categories` (
  `id` int(10) NOT NULL,
  `work_id` int(10) NOT NULL,
  `category_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `works_categories`
--

INSERT INTO `works_categories` (`id`, `work_id`, `category_id`) VALUES
(5, 6, 8),
(6, 8, 7),
(7, 8, 11),
(9, 9, 12),
(10, 9, 6),
(11, 10, 7),
(13, 11, 7),
(14, 11, 4),
(15, 12, 7),
(16, 12, 12),
(17, 13, 10),
(20, 14, 7),
(21, 14, 8),
(22, 15, 7),
(23, 15, 4),
(24, 16, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `work_id` (`work_id`);

--
-- Индексы таблицы `favourites`
--
ALTER TABLE `favourites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `work_id` (`work_id`);

--
-- Индексы таблицы `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_work` (`user_id`,`work_id`),
  ADD KEY `work_id` (`work_id`);

--
-- Индексы таблицы `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `follower_id` (`follower_id`),
  ADD KEY `following_id` (`following_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `works`
--
ALTER TABLE `works`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `works_categories`
--
ALTER TABLE `works_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `work_id` (`work_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `favourites`
--
ALTER TABLE `favourites`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT для таблицы `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `works`
--
ALTER TABLE `works`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT для таблицы `works_categories`
--
ALTER TABLE `works_categories`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`work_id`) REFERENCES `works` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `favourites`
--
ALTER TABLE `favourites`
  ADD CONSTRAINT `favourites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favourites_ibfk_2` FOREIGN KEY (`work_id`) REFERENCES `works` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`work_id`) REFERENCES `works` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `subscribers`
--
ALTER TABLE `subscribers`
  ADD CONSTRAINT `subscribers_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subscribers_ibfk_2` FOREIGN KEY (`following_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `works`
--
ALTER TABLE `works`
  ADD CONSTRAINT `works_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `works_categories`
--
ALTER TABLE `works_categories`
  ADD CONSTRAINT `works_categories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `works_categories_ibfk_2` FOREIGN KEY (`work_id`) REFERENCES `works` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
