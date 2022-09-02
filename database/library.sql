-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Сен 02 2022 г., 23:34
-- Версия сервера: 8.0.30-0ubuntu0.20.04.2
-- Версия PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `library`
--

-- --------------------------------------------------------

--
-- Структура таблицы `administrators`
--

CREATE TABLE `administrators` (
  `id` int NOT NULL,
  `name` char(255) NOT NULL,
  `surname` char(255) NOT NULL,
  `password` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `books`
--

CREATE TABLE `books` (
  `id` int NOT NULL,
  `book_name` char(255) NOT NULL,
  `book_creator` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `book_count`
--

CREATE TABLE `book_count` (
  `book_id` int NOT NULL,
  `count` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `book_in_ganre`
--

CREATE TABLE `book_in_ganre` (
  `book_id` int NOT NULL,
  `ganre_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `ganre`
--

CREATE TABLE `ganre` (
  `id` int NOT NULL,
  `genre` char(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `place_of_residence_user`
--

CREATE TABLE `place_of_residence_user` (
  `user_id` int NOT NULL,
  `country` char(55) NOT NULL,
  `city` char(255) NOT NULL,
  `street` char(255) NOT NULL,
  `house` int NOT NULL,
  `floor` int DEFAULT NULL,
  `flat` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users_book`
--

CREATE TABLE `users_book` (
  `user_id` int NOT NULL,
  `book_id` int NOT NULL,
  `who_issued` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_library`
--

CREATE TABLE `user_library` (
  `id` int NOT NULL,
  `name` char(55) NOT NULL,
  `surname` char(55) NOT NULL,
  `passport_first` int NOT NULL,
  `passport_last` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `years`
--

CREATE TABLE `years` (
  `id` int NOT NULL,
  `year_of_issue` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `years_books`
--

CREATE TABLE `years_books` (
  `book_id` int NOT NULL,
  `years_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `administrators`
--
ALTER TABLE `administrators`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `book_count`
--
ALTER TABLE `book_count`
  ADD KEY `book_id` (`book_id`);

--
-- Индексы таблицы `book_in_ganre`
--
ALTER TABLE `book_in_ganre`
  ADD KEY `book_id` (`book_id`),
  ADD KEY `ganre_id` (`ganre_id`);

--
-- Индексы таблицы `ganre`
--
ALTER TABLE `ganre`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `place_of_residence_user`
--
ALTER TABLE `place_of_residence_user`
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `users_book`
--
ALTER TABLE `users_book`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `who_issued` (`who_issued`);

--
-- Индексы таблицы `user_library`
--
ALTER TABLE `user_library`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `years`
--
ALTER TABLE `years`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `years_books`
--
ALTER TABLE `years_books`
  ADD KEY `book_id` (`book_id`),
  ADD KEY `years_id` (`years_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `administrators`
--
ALTER TABLE `administrators`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `books`
--
ALTER TABLE `books`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `ganre`
--
ALTER TABLE `ganre`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_library`
--
ALTER TABLE `user_library`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `years`
--
ALTER TABLE `years`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `book_count`
--
ALTER TABLE `book_count`
  ADD CONSTRAINT `book_count_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `book_in_ganre`
--
ALTER TABLE `book_in_ganre`
  ADD CONSTRAINT `book_in_ganre_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `book_in_ganre_ibfk_2` FOREIGN KEY (`ganre_id`) REFERENCES `ganre` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `place_of_residence_user`
--
ALTER TABLE `place_of_residence_user`
  ADD CONSTRAINT `place_of_residence_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_library` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `users_book`
--
ALTER TABLE `users_book`
  ADD CONSTRAINT `users_book_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_library` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `users_book_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `users_book_ibfk_3` FOREIGN KEY (`who_issued`) REFERENCES `administrators` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `years_books`
--
ALTER TABLE `years_books`
  ADD CONSTRAINT `years_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `years_books_ibfk_2` FOREIGN KEY (`years_id`) REFERENCES `years` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
