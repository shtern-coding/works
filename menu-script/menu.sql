-- phpMyAdmin SQL Dump
-- version 4.5.3.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Сен 29 2016 г., 09:26
-- Версия сервера: 5.5.46
-- Версия PHP: 7.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `work`
--

-- --------------------------------------------------------

--
-- Структура таблицы `menu`
--

CREATE TABLE `menu` (
  `id` int(3) NOT NULL,
  `title` varchar(255) NOT NULL,
  `parent_id` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `menu`
--

INSERT INTO `menu` (`id`, `title`, `parent_id`) VALUES
(1, 'Настенная', 0),
(2, 'Напольная и керамогранит', 0),
(3, 'Мозайка', 0),
(4, 'Фасадная', 0),
(5, 'Ступени', 0),
(6, 'Зеркальная', 0),
(7, 'Италия', 1),
(8, 'Испания', 1),
(9, 'Россия', 1),
(10, 'Турция', 1),
(11, 'Equipe', 8),
(12, 'Cevica', 8),
(13, 'Ceracasa', 8),
(14, 'Atlas Concorde', 7),
(15, 'Fap Ceramiche', 7),
(16, 'Naxos', 7),
(17, 'Emil Ceramica', 7),
(18, 'AXI', 14),
(19, 'AXI', 14),
(20, 'ETIC', 14),
(21, 'ETIC PRO', 14);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
