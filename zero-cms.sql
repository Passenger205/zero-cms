-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Май 17 2018 г., 09:42
-- Версия сервера: 5.7.22-0ubuntu18.04.1
-- Версия PHP: 7.2.4-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `zero-cms`
--

-- --------------------------------------------------------

--
-- Структура таблицы `aliases`
--

CREATE TABLE `aliases` (
  `id` int(11) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `aliases`
--

INSERT INTO `aliases` (`id`, `alias`, `address`) VALUES
(1, 'novosti', 'news/show/id/123');

-- --------------------------------------------------------

--
-- Структура таблицы `errors`
--

CREATE TABLE `errors` (
  `id` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `text` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `errors`
--

INSERT INTO `errors` (`id`, `code`, `title`, `text`) VALUES
(1, 404, 'ОШИБКА 404: Страница не найдена!', 'Вы запросили страницу, которой не существует.');

-- --------------------------------------------------------

--
-- Структура таблицы `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `order` int(11) NOT NULL,
  `container` varchar(50) DEFAULT NULL,
  `separator` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `link` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` tinytext NOT NULL,
  `text` mediumtext NOT NULL,
  `announce` text NOT NULL,
  `cover` text,
  `class` tinytext NOT NULL,
  `type` tinytext NOT NULL,
  `autor` tinytext NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `posts`
--

INSERT INTO `posts` (`id`, `title`, `text`, `announce`, `cover`, `class`, `type`, `autor`, `date`) VALUES
(1, '2PAC - Short biography', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'Lorem ipsum dolor sit amet..', 'post-2.png', 'top', 'text', 'admin', '2018-05-06 05:12:05'),
(2, 'Fahrenheit 451 - burning legend', 'Fahrenheit 451 is a dystopian novel by Ray Bradbury published in 1953. It is regarded as one of his best works. The novel presents a future American society where books are outlawed and \"firemen\" burn any that are found.Fahrenheit 451 is a dystopian novel by Ray Bradbury published in 1953. It is regarded as one of his best works. The novel presents a future American society where books are outlawed and \"firemen\" burn any that are found.', 'Fahrenheit 451 is a dystopian novel by Ray Bradbury published in 1953. It is regarded as one of his best works. The novel presents a future American society where books are outlawed and \"firemen\" burn any that are found.', 'post-3.png', 'left', 'text', 'admin', '2018-05-06 05:27:31'),
(3, 'The FBI privacy politics', 'The FBI claims it doesn\'t actually know how its iPhone-hacking tool works, so it can\'t share the method with Apple.The FBI claims it doesn\'t actually know how its iPhone-hacking tool works, so it can\'t share the method with Apple.The FBI claims it doesn\'t actually know how its iPhone-hacking tool works, so it can\'t share the method with Apple.', 'The FBI claims it doesn\'t actually know how its iPhone-hacking tool works, so it can\'t share the method with Apple.', 'post-1.png', 'top', 'text', 'admin', '2018-05-06 05:45:18'),
(4, 'Jules Gabriel Verne was not just a French novelist', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'French naturalist Dr. Aronnax embarks on an expedition to hunt down a sea monster, only to discover instead the Nautilus, a remarkable submarine built by the enigmatic Captain Nemo. Together Nemo and ...', 'post-6.jpg', 'right', 'text', 'admin', '2018-05-06 05:49:19');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `aliases`
--
ALTER TABLE `aliases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alias` (`alias`);

--
-- Индексы таблицы `errors`
--
ALTER TABLE `errors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Индексы таблицы `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order` (`order`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `aliases`
--
ALTER TABLE `aliases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `errors`
--
ALTER TABLE `errors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
