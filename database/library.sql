-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Сен 05 2022 г., 00:12
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

DELIMITER $$
--
-- Функции
--
CREATE DEFINER=`thrackerzod`@`localhost` FUNCTION `sp_book_add` (`book_value` CHAR(255), `ganre_value` CHAR(255), `years_value` INT(11), `count_value` INT(11), `creator_value` CHAR(255)) RETURNS INT NO SQL
BEGIN
	
SET @BOOK_NAME = (SELECT case WHEN COUNT(books.id) = 1
    THEN 'FIND'
    ELSE 'NOTFOUND'
END BOOK_NAME
    FROM `books`
WHERE
    `books`.`book_name` = `book_value`
                 AND
    `books`.`book_creator` = `creator_value`);
    
SET @BOOK_GANRE = (SELECT case WHEN COUNT(ganre.id) = 1
    THEN 'FIND'
    ELSE 'NOTFOUND'
END BOOK_GANRE
    FROM `ganre`
WHERE
    `ganre`.`ganre` = `ganre_value`);
    
SET @BOOK_YEARS = (SELECT case WHEN COUNT(years.id) = 1
    THEN 'FIND'
    ELSE 'NOTFOUND'
END BOOK_YEARS
    FROM `years`
WHERE
    `years`.`year_of_issue` = `years_value`);

    IF (@BOOK_NAME = 'NOTFOUND') THEN

		INSERT INTO books 
        (book_name, book_creator)
        VALUES
        (book_value, creator_value);
        
        SET @BOOK_ID = (SELECT LAST_INSERT_ID());

         IF (@BOOK_GANRE = 'NOTFOUND') THEN
			INSERT INTO 
            ganre (ganre)
            VALUES (ganre_value);
         END IF;

         INSERT INTO 
         book_in_ganre (book_id, ganre_id)
         VALUES 
         (
          @BOOK_ID, 
          (SELECT id FROM ganre 
           WHERE ganre = ganre_value
          LIMIT 1)
         );

         IF (@BOOK_YEARS = 'NOTFOUND') THEN
			INSERT INTO 
            years (year_of_issue)
            VALUES (years_value);
         END IF;
         
         INSERT INTO 
         years_books (book_id, years_id)
         VALUES 
         (
          (@BOOK_ID), 
          (SELECT id FROM years WHERE year_of_issue = years_value)
         );

        INSERT INTO book_count 
        (book_id, count)
        VALUES
        (
            (@BOOK_ID)
            ,
            count_value
        );
        
        RETURN 1;   
    ELSE
    
    	RETURN 0;
    END IF;

END$$

CREATE DEFINER=`thrackerzod`@`localhost` FUNCTION `sp_check_user_add` (`name_value` CHAR(55), `surname_value` CHAR(55), `first_value` INT(11), `last_value` INT(11)) RETURNS INT NO SQL
BEGIN
	
SET @USER_FIND = (SELECT case WHEN COUNT(user_library.id) = 1
		THEN 'FIND'
    	ELSE 'NOTFOUND'
END USER_FIND
     	FROM user_library
         WHERE
         	user_library.passport_first = `first_value`
         AND
         	user_library.passport_last = `last_value`);

IF (@USER_FIND = 'NOTFOUND') THEN
	INSERT INTO user_library
    (name, surname, passport_first, passport_last)
    VALUES
    (`name_value`, `surname_value`, `first_value`, `last_value`);
    
    return 1;
ELSE
	RETURN 0;
END IF;

END$$

CREATE DEFINER=`thrackerzod`@`localhost` FUNCTION `sp_delete_user` (`id_value` INT(11), `name_value` CHAR(55), `surname_value` CHAR(55), `first_value` INT(11), `last_value` INT(11)) RETURNS INT NO SQL
BEGIN

SET @USER_FIND = (SELECT case WHEN COUNT(user_library.id) = 1
		THEN 'FIND'
    	ELSE 'NOTFOUND'
END USER_FIND
     	FROM `user_library`
         WHERE
        `id` = `id_value`);

IF (@USER_FIND = 'FIND') THEN
    DELETE FROM 
    `user_library`
    WHERE 
        `user_library`.`id` = `id_value`
    AND
        `user_library`.`name` = `name_value`
    AND  
        `user_library`.`surname` = `surname_value`
    AND 
        `user_library`.`passport_first` = `first_value`
    AND
        `user_library`.`passport_last` = `last_value`;
    
    RETURN 1;
ELSE

	RETURN 0;
END IF;

END$$

CREATE DEFINER=`thrackerzod`@`localhost` FUNCTION `sp_update_book` (`book_value` CHAR(255), `ganre_value` CHAR(255), `years_value` INT(11), `count_value` INT(11), `creator_value` CHAR(255), `id_value` INT(11)) RETURNS INT NO SQL
BEGIN

SET @BOOK_ID = (SELECT case WHEN COUNT(books.id) = 1
    THEN 'FIND'
    ELSE 'NOTFOUND'
END BOOK_ID
    FROM `books`
WHERE
    `books`.`id` = `id_value`);

IF (@BOOK_NAME = 'NOTFOUND') THEN
	RETURN 0;
ELSE

    IF((LENGTH(`book_value`)) > 1) THEN
    	UPDATE books
        SET books.book_name = `book_value`
        WHERE books.id = `id_value`;
    END IF;

    IF((LENGTH(`creator_value`)) > 1) THEN
		UPDATE books
        SET books.book_creator = `creator_value`
        WHERE books.id = `id_value`;
    END IF;

    IF((LENGTH(`ganre_value`)) > 1) THEN
    
        SET @GANRE_FIND = (SELECT case WHEN COUNT(ganre.id) = 1
            THEN 'FIND'
            ELSE 'NOTFOUND'
        END GANRE_FIND
            FROM `ganre`
            WHERE
            ganre.ganre = `ganre_value`);
        
    
        IF(@GANRE_FIND = 'FIND') THEN
        
            SET @GANRE_ID = (SELECT `id` 
                FROM `ganre`
                WHERE `ganre`.`ganre` = `ganre_value`);

            UPDATE `book_in_ganre`
            SET `ganre_id` = @GANRE_ID
            WHERE `book_in_ganre`.`book_id` = `id_value`;

        ELSE
        
        	INSERT INTO `ganre` (`ganre`) 
            VALUES (`ganre_value`);
            
            UPDATE book_in_ganre
            SET `ganre_id` = (SELECT LAST_INSERT_ID())
            WHERE `book_in_ganre`.`book_id` = `id_value`;
            
        END IF;  

    END IF;

    IF(`years_value` > 0) THEN
        
        SET @YEARS_FIND = (SELECT case WHEN COUNT(years.id) = 1
            THEN 'FIND'
            ELSE 'NOTFOUND'
        END YEARS_FIND
            FROM `years`
            WHERE
            `years`.`year_of_issue` = `years_value`);
        
    
        IF(@YEARS_FIND = 'FIND') THEN
        
            SET @YEARS_ID = (SELECT `id` 
                FROM `years`
                WHERE `years`.`year_of_issue` = `years_value`);

            UPDATE `years_books`
            SET `years_books`.`years_id` = @YEARS_ID
            WHERE `years_books`.`book_id` = `id_value`;

        ELSE
        
        	INSERT INTO years (`year_of_issue`) 
            VALUES (`years_value`);
            
            UPDATE `years_books`
            SET `years_books`.`years_id` = (SELECT LAST_INSERT_ID())
            WHERE `years_books`.`book_id` = `id_value`;
            
        END IF;  

    END IF;
    
    IF(`count_value` > 0) THEN
		UPDATE book_count
        SET book_count.count = `count_value`
        WHERE book_count.book_id = `id_value`;
    END IF;
    
	RETURN 1;
    
END IF;

END$$

CREATE DEFINER=`thrackerzod`@`localhost` FUNCTION `sp_update_user` (`id_value` INT(11), `name_value` CHAR(55), `surname_value` CHAR(55), `first_value` INT(11), `last_value` INT(11)) RETURNS INT NO SQL
BEGIN

SET @USER_FIND = (SELECT case WHEN COUNT(user_library.id) = 1
		THEN 'FIND'
    	ELSE 'NOTFOUND'
END USER_FIND
     	FROM `user_library`
         WHERE
        `id` = `id_value`);

IF (@USER_FIND = 'FIND') THEN
	UPDATE `user_library`
    
    SET 
        `user_library`.`name` = `name_value`, 
        `user_library`.`surname` = `surname_value`,
        `user_library`.`passport_first` = `first_value`,
        `user_library`.`passport_last` = `last_value`
    WHERE 
    	`user_library`.`id` = `id_value`;
    
    RETURN 1;
ELSE

	RETURN 0;
END IF;

END$$

DELIMITER ;

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

--
-- Дамп данных таблицы `books`
--

INSERT INTO `books` (`id`, `book_name`, `book_creator`) VALUES
(21, 'тень над исмутом', 'Г.Ф.Ловкрафт'),
(24, 'тень над исмутом d', 'Г.Ф.Ловкрафт'),
(26, 'тень над исмутом', 'Г.Ф.Ловкрафтs'),
(27, 'тень над исмутом f', 'Г.Ф.Ловкрафт'),
(28, 'тень над исмутом', 'Г.Ф.Ловкрафтa ff'),
(29, '6', '1234');

-- --------------------------------------------------------

--
-- Структура таблицы `book_count`
--

CREATE TABLE `book_count` (
  `book_id` int NOT NULL,
  `count` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `book_count`
--

INSERT INTO `book_count` (`book_id`, `count`) VALUES
(21, 3),
(24, 3),
(27, 3),
(28, 3),
(29, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `book_in_ganre`
--

CREATE TABLE `book_in_ganre` (
  `book_id` int NOT NULL,
  `ganre_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `book_in_ganre`
--

INSERT INTO `book_in_ganre` (`book_id`, `ganre_id`) VALUES
(21, 19),
(24, 19),
(27, 19),
(28, 19),
(29, 20);

-- --------------------------------------------------------

--
-- Структура таблицы `ganre`
--

CREATE TABLE `ganre` (
  `id` int NOT NULL,
  `ganre` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `ganre`
--

INSERT INTO `ganre` (`id`, `ganre`) VALUES
(19, 'хоррор'),
(20, 'user');

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

--
-- Дамп данных таблицы `years`
--

INSERT INTO `years` (`id`, `year_of_issue`) VALUES
(3, 1917),
(4, 1997);

-- --------------------------------------------------------

--
-- Структура таблицы `years_books`
--

CREATE TABLE `years_books` (
  `book_id` int NOT NULL,
  `years_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `years_books`
--

INSERT INTO `years_books` (`book_id`, `years_id`) VALUES
(21, 3),
(24, 3),
(27, 3),
(28, 3),
(29, 4);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT для таблицы `ganre`
--
ALTER TABLE `ganre`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `user_library`
--
ALTER TABLE `user_library`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `years`
--
ALTER TABLE `years`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
