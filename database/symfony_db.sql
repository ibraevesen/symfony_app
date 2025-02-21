-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Фев 20 2025 г., 10:41
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `symfony_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `buy_history`
--

CREATE TABLE `buy_history` (
  `id` int(11) NOT NULL,
  `model_id` int(11) DEFAULT NULL,
  `amount_paid` double NOT NULL,
  `paypal_transaction_id` varchar(255) NOT NULL,
  `datetime_paid` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `buy_history`
--

INSERT INTO `buy_history` (`id`, `model_id`, `amount_paid`, `paypal_transaction_id`, `datetime_paid`) VALUES
(1, 6, 1000, '6WV26084HT376035L', '2025-02-15 11:56:33'),
(2, 4, 1000, '97A53226NN798502B', '2025-02-15 13:02:53');

-- --------------------------------------------------------

--
-- Структура таблицы `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `brand` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `cars`
--

INSERT INTO `cars` (`id`, `brand`) VALUES
(1, 'BMW'),
(2, 'Mercedes'),
(3, 'Toyota'),
(4, 'Honda'),
(5, 'KIA'),
(13, 'Lada'),
(14, 'Mazda');

-- --------------------------------------------------------

--
-- Структура таблицы `car_models`
--

CREATE TABLE `car_models` (
  `id` int(11) NOT NULL,
  `car_id` int(11) DEFAULT NULL,
  `model` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `model_price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `car_models`
--

INSERT INTO `car_models` (`id`, `car_id`, `model`, `description`, `photo`, `model_price`) VALUES
(4, 2, 'W222', 'Mercedes-Benz W222 — шестое поколение флагманской серии представительских автомобилей S-класса немецкой марки Mercedes-Benz, выпускающееся с 2013 года. Пришло на смену модели W221. Разработкой дизайна, начатой ещё в 2009 году, занимался Роберт Лесник. Экстерьер новой модели позаимствован у CLA-класса и первого поколения W212 E-класса. Презентация автомобиля состоялась 15 мая 2013 года в Гамбурге, Германия[3].Выпускается в вариантах кузова седан (с укороченной и удлинённой колёсными базами), купе и кабриолет (с 2015 года). Кроме того, имеет высокопроизводительные модификации от подразделения Mercedes-AMG в лице S63 AMG и S65 AMG (оба доступны в кузовах седан, купе и кабриолет), а также наиболее роскошную версию лимузин — Pullman, собранный суббрендом Mercedes-Maybach. Помимо заводского подразделения AMG автомобиль пользуется популярностью у различных тюнинг-ателье .', '/uploads/679f5a0f4366b.webp', 1000),
(6, 1, 'X5', 'BMW F15 — третье поколение знаменитого среднеразмерного кроссовера BMW X5 немецкой компании BMW. Выпуск модели был начат в ноябре 2013 года в Европе. Одновременно с запуском новой модели с производства была снята предыдущая — E70.', '/uploads/679f5977ce976.webp', 999),
(7, 1, 'e36', 'BMW M3 (E36) — второе поколение BMW M3. Было представлено в 1992 году на Парижском автосалоне. Одновременно с запуском новой модели с производства была снята предыдущая E30[1].\n\nЭто был первый автомобиль, укомплектованный шестицилиндровым двигателем BMW S50, который заменил предыдущую модель BMW S14[2]. С 1994 года автомобиль выпускался в кузовах кабриолет и седан. BMW M3 E36 являлся первым автомобилем BMW M с «правым рулём»[3].\n\nПроизводство модели завершилось в 1999 году[4].', '/uploads/679f59425770e.jpg', 1000),
(8, 14, 'CX-5', 'Mazda CX-5 — компактный кроссовер, выпускаемый японской автомобильной компанией Mazda. Первый серийный автомобиль Mazda, дизайн кузова которого создан согласно идеологии KODO — Дух движения. Также это первая модель в рамках технической концепции Skyactiv Technology, нацеленной на снижение массы всех агрегатов автомобиля без снижения характеристик эффективности и безопасности. Модель заменила компактный кроссовер Mazda Tribute[2]. В модельном ряду Mazda CX-5 занимает место между более компактным CX-30 и полноразмерным CX-9.\n\nКроссовер является победителем ежегодной премии Автомобиль года в Японии 2012-2013 годов.\n\nMazda CX-5 — компактный кроссовер, выпускаемый японской автомобильной компанией Mazda. Первый серийный автомобиль Mazda, дизайн кузова которого создан согласно идеологии KODO — Дух движения. Также это первая модель в рамках технической концепции Skyactiv Technology, нацеленной на снижение массы всех агрегатов автомобиля без снижения характеристик эффективности и безопасности. Модель заменила компактный кроссовер Mazda Tribute[2]. В модельном ряду Mazda CX-5 занимает место между более компактным CX-30 и полноразмерным CX-9.\n\nКроссовер является победителем ежегодной премии Автомобиль года в Японии 2012-2013 годов.\n\nMazda CX-5 — компактный кроссовер, выпускаемый японской автомобильной компанией Mazda. Первый серийный автомобиль Mazda, дизайн кузова которого создан согласно идеологии KODO — Дух движения. Также это первая модель в рамках технической концепции Skyactiv Technology, нацеленной на снижение массы всех агрегатов автомобиля без снижения характеристик эффективности и безопасности. Модель заменила компактный кроссовер Mazda Tribute[2]. В модельном ряду Mazda CX-5 занимает место между более компактным CX-30 и полноразмерным CX-9.\n\nКроссовер является победителем ежегодной премии Автомобиль года в Японии 2012-2013 годов.', '/uploads/679f5b8b56f97.png', 1000),
(9, 4, 'Civic', 'Honda Civic (рус. Хо́нда Сиви́к) — большое и разнообразное семейство легковых автомобилей, выпускаемых японской компанией Honda Motor с 1972 года.Это самая популярная модель компании, к пятидесятилетнему юбилею, отмечавшемуся летом 2022 года, выпущено более 27,5 миллиона автомобилей[1]. Civic неоднократно выбирался Автомобилем года в Японии[2][3][4][5] и Северной Америке[6][7][8]. Благодаря Civic компания Honda, известная как крупнейший производитель мотоциклов, стала признанным мировым производителем автомобилей[9].', '/uploads/679f5d9cf14e8.webp', 1000),
(11, 1, 'qwe', 'wdadwa', NULL, 334);

-- --------------------------------------------------------

--
-- Структура таблицы `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250129123828', '2025-01-29 13:39:36', 58),
('DoctrineMigrations\\Version20250202111753', '2025-02-02 12:18:30', 32),
('DoctrineMigrations\\Version20250205103125', '2025-02-05 11:32:23', 46),
('DoctrineMigrations\\Version20250207122234', '2025-02-07 13:23:42', 86);

-- --------------------------------------------------------

--
-- Структура таблицы `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `roles`, `password`) VALUES
(2, 'sam', '[]', '$2y$13$ipbr7ZgLy11uA9J8F3VmOuXcaMEUal2BY8slD405Ln8wtzmZhcRNi'),
(3, 'admin', '[\"ROLE_ADMIN\"]', '$2y$13$yWxfd4W6f6kgrdnU9zu0dOx7P4f8NTSkLywDK5yzwCUdQy0GGAfeq');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `buy_history`
--
ALTER TABLE `buy_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_198FEB187975B7E7` (`model_id`);

--
-- Индексы таблицы `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `car_models`
--
ALTER TABLE `car_models`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_FCBEDCFBC3C6F69F` (`car_id`);

--
-- Индексы таблицы `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Индексы таблицы `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_USERNAME` (`username`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `buy_history`
--
ALTER TABLE `buy_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `car_models`
--
ALTER TABLE `car_models`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `buy_history`
--
ALTER TABLE `buy_history`
  ADD CONSTRAINT `FK_198FEB187975B7E7` FOREIGN KEY (`model_id`) REFERENCES `car_models` (`id`);

--
-- Ограничения внешнего ключа таблицы `car_models`
--
ALTER TABLE `car_models`
  ADD CONSTRAINT `FK_FCBEDCFBC3C6F69F` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
