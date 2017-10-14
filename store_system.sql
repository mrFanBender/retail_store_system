-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Окт 13 2017 г., 13:43
-- Версия сервера: 5.7.11
-- Версия PHP: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `store_system`
--

-- --------------------------------------------------------

--
-- Структура таблицы `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `full_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(256) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `companies`
--

INSERT INTO `companies` (`id`, `name`, `full_name`, `address`) VALUES
(1, 'ООО Компания Татарстан', 'Общество с ограниченной ответственностью Компания Татарстан', 'Татарстан, Казань, Фучика 18'),
(2, 'ИП Хабибуллин', 'Индивидуальный предприниматель Хабибуллин Р.М.', 'Екатеринбург');

-- --------------------------------------------------------

--
-- Структура таблицы `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) NOT NULL,
  `image` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `purchase_price` int(11) DEFAULT NULL,
  `large_opt_price` int(11) DEFAULT NULL,
  `medium_opt_price` int(11) DEFAULT NULL,
  `small_opt_price` int(11) DEFAULT NULL,
  `retail_price` int(11) DEFAULT NULL,
  `company_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `product`
--

INSERT INTO `product` (`id`, `name`, `description`, `parent_id`, `image`, `purchase_price`, `large_opt_price`, `medium_opt_price`, `small_opt_price`, `retail_price`, `company_id`) VALUES
(2, 'Amy deluxe 640', 'кальян эми 23', 9, 'нет изображения', 3600, 4200, 4700, 5300, 5790, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `product_group`
--

CREATE TABLE `product_group` (
  `id` int(11) NOT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `product_group`
--

INSERT INTO `product_group` (`id`, `name`, `description`, `parent_id`, `company_id`) VALUES
(9, 'Носки', 'Хлопковые носки', 7, 1),
(8, 'Воздушные шарики 3', 'Самые разные шарики3', 5, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `product_in_warehouse`
--

CREATE TABLE `product_in_warehouse` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `job` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `login` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `pass` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '123',
  `hash` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `confirm` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `name`, `job`, `login`, `pass`, `hash`, `description`, `admin`, `confirm`) VALUES
(1, 'Рустам', 'Директор', 'mrFanBender', 'stronghold', '', 'первый пользователь', 1, 0),
(2, 'rustam', NULL, 'rustam', '123', 'rw3qvjrJz1ygcNHc2vGm', NULL, 0, 0),
(3, 'rustam', NULL, 'rustam2', '123', '9txNrlLJOd55M8exz7fg', NULL, 0, 0),
(4, 'rustam', NULL, 'rustam3', '123', 'CPvXXHlNQEb6XX2ccBOM', NULL, 0, 0),
(5, 'rustam', NULL, 'rustam4', '123', 'rAvks0hzjLCzlSkXKWbs', NULL, 0, 0),
(6, 'rustam', NULL, 'rustam5', '123', '7SVgamkVBcwGcJu1xpgQ', NULL, 0, 0),
(7, 'rustam', NULL, 'rustam6', '123', 'BhWlEpLwYACRyWd6MAsL', NULL, 0, 0),
(8, 'rustam', NULL, 'rustam7', '123', 'fqgUv8l3Dv6WBKL2mkgz', NULL, 0, 0),
(9, 'rustam', NULL, 'rustam8', '123', 'quTW1qlx2kBdpiJ0pjqY', NULL, 0, 0),
(10, 'rustam', NULL, 'rustam9', '123', 'QBq2uxbCRqAQNUf3XLO7', NULL, 0, 0),
(11, 'rustam', NULL, 'rustam10', '123', 'wLBzmWQLRt9wN0rNOMZz', NULL, 0, 0),
(12, 'rustam', NULL, 'rustam11', '123', 'HEP1fQojwiivk0XK0tiD', NULL, 0, 0),
(13, 'rustam', NULL, 'rustam12', '123', 'Al9p003i7G66ngonnSzb', NULL, 0, 0),
(14, 'rustam', NULL, 'rustam13', '123', 'w2d4M9CB8wYoNd3tqRhr', NULL, 0, 0),
(15, 'rustam', NULL, 'rustam14', '123', 'YjsIDOOzoVYnCRDg8M61', NULL, 0, 0),
(16, 'rustam', NULL, 'rustam15', '123', 'jzR8D6sBtyF7LFyZGDRu', NULL, 0, 0),
(17, 'rustam', NULL, 'rustam16', '123', '8E5rIIKJhLX7Ijlr6Uyn', NULL, 0, 0),
(18, 'rustam', NULL, 'rustam17', '123', 'hP89dsH7Ts1bXX8LpW0M', NULL, 0, 0),
(19, 'rustam', NULL, 'rustam18', '123', '20GaoREqSnzXLGFJ61Pz', NULL, 0, 0),
(20, 'rustam', NULL, 'rustam19', '123', '3d4Qh1iPiEF6DoUe4D2r', NULL, 0, 0),
(21, 'rustam', NULL, 'rustam20', '123', 'x4Qptz5mRj9FZZCx5mJX', NULL, 0, 0),
(22, 'rustam', NULL, 'rustam21', '123', 'uGlUJBkMyzsOnZEkvttW', NULL, 0, 0),
(23, 'rustam', NULL, 'rustam23', '123', 'gz56LFZQQlVQPdDV2vp6', NULL, 0, 0),
(24, 'rustam', NULL, 'rustam24', '123', 'ME3VZWLRXOhujR0fZyCP', NULL, 0, 0),
(25, 'rustam', NULL, 'rustam25', '123', 'IPLgisdry5GeOQkor1rS', NULL, 0, 0),
(26, 'rustam', NULL, 'rustam26', '123', 'UlYVj4gblZYvgHUfGwCq', NULL, 0, 0),
(27, 'rustam', NULL, 'rustam27', '123', 'CBRinnmBNG8179lpOuoI', NULL, 0, 0),
(28, 'rustam', NULL, 'rustam28', '123', 'Akrfpm5gtcm8j2zgQ6pY', NULL, 0, 0),
(29, 'rustam', NULL, 'rustam29', '123', 'TYnVx6qeUN3sOyJZTjjV', NULL, 0, 0),
(30, 'rustam', NULL, 'rustam30', 'd9b1d7db4cd6e70935368a1efb10e377', '562da546a32a42b61b74ac58d0cd1b05', NULL, 0, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users_in_companies`
--

CREATE TABLE `users_in_companies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `users_in_companies`
--

INSERT INTO `users_in_companies` (`id`, `user_id`, `company_id`, `active`) VALUES
(1, 30, 1, 1),
(2, 30, 2, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `user_rights`
--

CREATE TABLE `user_rights` (
  `id` int(16) NOT NULL,
  `user_id` int(16) NOT NULL,
  `object_id` int(11) NOT NULL,
  `object_type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `right_code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `value` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `warehouse`
--

CREATE TABLE `warehouse` (
  `id` int(11) NOT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `company_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `warehouse`
--

INSERT INTO `warehouse` (`id`, `name`, `description`, `company_id`) VALUES
(2, 'Склад 3', 'Склад на Викулова', 1),
(3, 'Склад №4', 'Склад На Космонавтов', 2);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `product_group`
--
ALTER TABLE `product_group`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `product_in_warehouse`
--
ALTER TABLE `product_in_warehouse`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users_in_companies`
--
ALTER TABLE `users_in_companies`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_rights`
--
ALTER TABLE `user_rights`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `product_group`
--
ALTER TABLE `product_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT для таблицы `product_in_warehouse`
--
ALTER TABLE `product_in_warehouse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT для таблицы `users_in_companies`
--
ALTER TABLE `users_in_companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `user_rights`
--
ALTER TABLE `user_rights`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `warehouse`
--
ALTER TABLE `warehouse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
