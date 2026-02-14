-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Фев 11 2026 г., 09:07
-- Версия сервера: 8.0.39
-- Версия PHP: 8.2.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `autodetals`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL COMMENT 'ID пользователя (если авторизован)',
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1' COMMENT 'Количество товара',
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID сессии (для неавторизованных пользователей)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `categories_id` int DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 - активна, 0 - неактивна',
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `categories_id`, `name`, `code`, `description`, `is_active`, `image`, `created_at`, `updated_at`) VALUES
(4, NULL, 'Жидкости', 'jitkosti', 'Всё необходимое для вашего автомобиля: масла, антифризы, тормозные жидкости и другие автомобильные жидкости.', 1, 'categories/W61Ubtt2kkA40uIThDttjd4u2ji3fdqf0WvCaBnn.jpg', '2025-04-10 07:46:47', '2025-12-24 06:44:55'),
(5, NULL, 'Автоэлектроника', 'autoelectric', 'Все необходимое для электроники вашего автомобиля: камеры, навигаторы, аккумуляторы и другое.', 1, 'categories/vxxTsAWgJFu1jjxg8ArKWe8vglqrpmDS6lfmEKdz.jpg', '2025-04-10 07:47:03', '2025-05-26 06:27:28'),
(6, NULL, 'Автолампы', 'autolamp', 'Разнообразие автоламп для вашего автомобиля: фары, габариты, лампы для салона и другие.', 1, 'categories/FYKnRp6jUksRXuSMNJdJKIh1JorGaFWggyaWpe3I.jpg', '2025-04-10 07:47:18', '2025-05-26 06:27:42'),
(7, NULL, 'Диски', 'diski', 'Широкий ассортимент автомобильных дисков: литые, стальные, диски для различных марок автомобилей.', 1, 'categories/mR7PKrlkZnzD2feb0ttlyqiSAStnd9nQvoVcey2r.jpg', '2025-04-10 07:47:37', '2025-05-26 06:28:12'),
(8, NULL, 'Крепежные изделия', 'krepezniyizdeliya', 'Всё необходимое для крепления и установки частей вашего автомобиля: болты, гайки, шайбы и другие элементы.', 1, 'categories/v4Tt1IAxctNMDczsZsvP8PdEPD9kLYGPRzk3l0Bj.jpg', '2025-04-10 07:48:12', '2025-05-26 06:28:50'),
(9, NULL, 'Подвеска', 'podveska', 'Все необходимые компоненты подвески для вашего автомобиля: амортизаторы, пружины, стойки и другие детали.', 1, 'categories/V8BFsh9CSuDqBdFwic4BVi5ox2xAim784WEyezOk.jpg', '2025-04-10 07:48:33', '2025-05-26 06:29:38'),
(10, NULL, 'Шины', 'shiny', 'Выбор шин для вашего автомобиля: летние, зимние, всесезонные, а также другие виды и бренды.', 1, 'categories/zyln4Cr6Y2NrvISdwCt84RsaoTcPMDZSzl85QoV1.jpg', '2025-04-10 07:48:46', '2025-05-26 06:29:57'),
(11, NULL, 'Автохимия', 'autohimiya', 'Продукты автохимии для поддержания вашего автомобиля в отличном состоянии: жидкости, очищающие средства и многое другое.', 1, 'categories/eVezBZePei73LZc8krlaguR0lNLNZXYaD5O92CpD.jpg', '2025-04-10 07:49:02', '2025-05-26 06:30:15');

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('новый','в_пути','ожидает_на_пункте','получен','отменен') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'новый',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` double NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 - активен, 0 - неактивен',
  `popular` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `views` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Количество просмотров товара'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `code`, `description`, `images`, `price`, `is_active`, `popular`, `created_at`, `updated_at`, `views`) VALUES
(4, 4, 'Моторное масло', 'morotmaslo', 'Высококачественное масло для защиты двигателя от износа и продления его срока службы.', '\"[\\\"products\\\\\\/7KTHMAjX4j2vLn6jmPhdGsZTDl708VOgki3cAiMo.png\\\"]\"', 1200, 1, 0, '2025-04-10 07:53:32', '2025-12-24 07:11:23', 1),
(5, 4, 'Антифриз', 'antifreze', 'Обеспечивает эффективное охлаждение двигателя и защиту от перегрева.', '\"[\\\"products\\\\\\/73iPUqjCbbdmmJ6nIUdOn7ydE1637RebsUYuWl1t.png\\\"]\"', 800, 1, 0, '2025-04-10 07:55:59', '2025-05-26 06:32:16', 0),
(6, 4, 'Тормозная жидкость', 'tormoznayajitkost', 'Жидкость для тормозной системы, обеспечивающая безопасность и эффективное торможение.', '\"[\\\"products\\\\\\/bU2IHk9krp847mYl4hSLCzX6a55gZZnJTDALpHfI.png\\\"]\"', 650, 1, 0, '2025-04-10 07:56:53', '2025-05-26 06:32:33', 0),
(7, 5, 'Автомобильная камера', 'autocamera', 'Камера для записи всего, что происходит на дороге. Подходит для любых автомобилей.', '\"[\\\"products\\\\\\/2sN0uDdoHkkBMeypfZFsDEcEXPmn2KtBSQzb15zL.png\\\"]\"', 3500, 1, 0, '2025-04-10 07:58:41', '2025-05-26 06:32:48', 0),
(8, 5, 'GPS-навигатор', 'gpsnavigator', 'Навигатор с актуальными картами для точного маршрута и удобного путешествия.', '\"[\\\"products\\\\\\/Q2cSBGVf6u2ORc5mOv73sAcmoA9a22QhrgERD72k.png\\\"]\"', 4200, 1, 0, '2025-04-10 07:59:30', '2025-12-24 07:05:25', 1),
(9, 5, 'Автомобильный аккумулятор', 'acum', 'Надежный автомобильный аккумулятор для большинства марок автомобилей.', '\"[\\\"products\\\\\\/mM6J28RNyD123bR8GItjYEFpeYq9AyVKbZ6scgFR.png\\\"]\"', 5000, 1, 1, '2025-04-10 08:00:36', '2025-12-24 07:20:52', 23),
(10, 6, 'Лампа головного света', 'lampheadlight', 'Мощная лампа для вашего автомобиля с высокой яркостью и долговечностью.', '\"[\\\"products\\\\\\/kTtkkd4n4e2V0hBfniFHOz71ZXk2hWC1xFnGnwGo.png\\\"]\"', 350, 1, 0, '2025-04-10 08:03:44', '2025-05-26 06:36:36', 0),
(11, 6, 'Противотуманная лампа', 'protivotumanlamp', 'Идеальна для улучшенной видимости в условиях тумана и плохой видимости.', '\"[\\\"products\\\\\\/OCA4QSAmYpr7syoAGhO6or4V2gNnGBSrlXeLJQNV.jpg\\\"]\"', 500, 1, 0, '2025-04-10 08:04:17', '2025-05-26 06:36:46', 0),
(12, 6, 'Лампа указателя поворота', 'lampukazpovorota', 'Обеспечьте надежную работу сигнализации с лампами поворота.', '\"[\\\"products\\\\\\/JXUs9msaYCnUK2bNwfCEn55yxlcsmuB3jxaVRMkX.jpg\\\"]\"', 150, 1, 0, '2025-04-10 08:06:10', '2025-05-26 06:37:22', 0),
(13, 7, 'Литой диск \"16\"', 'd16', 'Легкий и прочный литой диск для легковых автомобилей, улучшает внешность и характеристики автомобиля.', '\"[\\\"products\\\\\\/7Mtc7luO1qTaXDEIqPQQ1dzn8dAWvv1be8OqdF7S.jpg\\\"]\"', 8500, 1, 1, '2025-04-10 08:07:22', '2025-12-23 19:53:04', 3),
(14, 7, 'Стальной диск \"15\"', 'd15', 'Прочный и надежный стальной диск, идеален для зимнего использования.', '\"[\\\"products\\\\\\/hNOpErp9jGdTLfQJoh0rQwdJCoYnxZ7GVYadmFSk.png\\\"]\"', 4000, 1, 0, '2025-04-10 08:08:29', '2025-05-26 06:38:04', 0),
(15, 7, 'Диск с индивидуальным дизайном', 'dizaynerdisk', 'иски с уникальным дизайном для автомобиля. Подходит для тех, кто ценит стиль.', '\"[\\\"products\\\\\\/Ctrprxvf5s68S9nMYr7ZdMTTnLyiT3LqljlBWPXc.jpg\\\"]\"', 12000, 1, 0, '2025-04-10 08:09:39', '2025-05-26 06:38:52', 0),
(16, 8, 'Шайба стальная', 'shyba', 'Шайба для обеспечения плотности соединений и предотвращения повреждений поверхности.', '\"[\\\"products\\\\\\/yu7yn68O0s3y0aQlYYtgweRoWJSQSIqPMO8WhRyN.jpg\\\"]\"', 10, 1, 0, '2025-04-10 08:11:42', '2025-05-26 06:39:02', 0),
(17, 8, 'Клипса крепёжная', 'clipsa', 'Клипсы для крепления декоративных элементов и пластиковой отделки внутри автомобиля.', '\"[\\\"products\\\\\\/NLpiy30cG4eB6hapexYTnxh0sgN3aAapvP7T3qel.jpg\\\"]\"', 50, 1, 0, '2025-04-10 08:12:43', '2025-05-26 06:40:21', 0),
(18, 8, 'Саморез автомобильный', 'samorezcar', 'Саморезы с защитным покрытием для крепления различных частей авто, особенно для работы с пластиком и металлом.', '\"[\\\"products\\\\\\/cb5oqxit56LoXUcnD2O5imbjlvC7RHB37GWsr2FS.png\\\"]\"', 30, 1, 0, '2025-04-10 08:13:25', '2025-05-26 06:40:13', 0),
(19, 9, 'Рычаг подвески', 'richag', 'Рычаг подвески для надежного крепления колесных узлов. Изготовлен из прочного материала.', '\"[\\\"products\\\\\\/z5mMZOQEiK0SfU4Zk4xd0mHflIEDqUFFd8JEsdWz.jpg\\\"]\"', 4800, 1, 0, '2025-04-10 08:14:41', '2025-05-26 06:40:39', 0),
(20, 9, 'Стойка подвески', 'stoyka', 'Комбинированная стойка с амортизатором для улучшения комфорта и безопасности при вождении.', '\"[\\\"products\\\\\\/0UNE1TDbRzm4vOeopBnBY0AZGMvvWyKC75YIkTSq.jpg\\\"]\"', 6000, 1, 0, '2025-04-10 08:15:45', '2025-12-23 20:14:47', 1),
(21, 9, 'Шаровая опора', 'sharovayaopora', 'Шаровая опора для подвески, обеспечивает плавность движения и стойкость к износу.', '\"[\\\"products\\\\\\/xzbkOB9JVPoNVg0WCgQZtlpdcHwQEiyEYtUGEgo4.jpg\\\"]\"', 1200, 1, 0, '2025-04-10 08:16:21', '2025-05-26 06:41:01', 0),
(22, 10, 'Летняя шина Michelin Pilot Sport 4', 'summershina', 'Летняя шина для легковых автомобилей. Отличная управляемость и сцепление с дорогой в жаркую погоду.', '\"[\\\"products\\\\\\/N1ZMJdXjTaN4shw4nzobYUX7vTs0wWRBZooZiAk5.png\\\"]\"', 7500, 1, 0, '2025-04-10 08:18:12', '2025-05-26 06:41:20', 0),
(23, 10, 'Зимняя шина Bridgestone Blizzak LM-005', 'wintershina', 'Зимняя шина для отличного сцепления с дорогой в условиях снега и льда. Устойчивость на зимних дорогах.', '\"[\\\"products\\\\\\/VIIIn1815rZoDIiiD4Y9dphz8qCcUfGkblprycr8.png\\\"]\"', 9000, 1, 0, '2025-04-10 08:18:55', '2025-05-26 06:41:31', 0),
(24, 10, 'Набор для шиномонтажа', 'shinakit', 'Комплект для монтажа и демонтажа шин. Включает в себя все необходимое для шиномонтажа.', '\"[\\\"products\\\\\\/XHYbVQ9d0USBeMmiUrpUUuCwXYtGC72elKviuOCD.png\\\"]\"', 1500, 1, 0, '2025-04-10 08:19:34', '2025-05-26 06:41:41', 0),
(25, 11, 'Жидкость для омывателя стекол', 'grassclear', 'Жидкость для эффективного очищения стекол автомобиля от грязи, пыли и насекомых.', '\"[\\\"products\\\\\\/JHiE6SL4QN4WGL7WhwDYLVMb54F11Z23ZN24nsfp.jpg\\\"]\"', 350, 1, 1, '2025-04-10 08:20:54', '2025-12-24 07:07:03', 2),
(26, 11, 'Средство для ухода за шинами', 'uhodzashinamy', 'Средство для защиты и ухода за шинами, продлевающее срок службы и придающее блеск.', '\"[\\\"products\\\\\\/4t110KvVLLiP6Yi47y2hearQS6mq57eiFDZSS0H1.jpg\\\"]\"', 550, 1, 0, '2025-04-10 08:21:30', '2025-05-26 06:42:42', 0),
(27, 11, 'Очиститель для двигателя', 'clearonengine', 'Эффективный очиститель для двигателя, устраняющий загрязнения и улучшая работу системы охлаждения.', '\"[\\\"products\\\\\\/K57PSjRiJWVQ6WDeXY0fVWqzIl5Yob1nQWicFFLB.jpg\\\"]\"', 650, 1, 0, '2025-04-10 08:22:13', '2025-05-26 06:42:59', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `rating` int NOT NULL,
  `advantages` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `disadvantages` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_approved` tinyint(1) DEFAULT NULL COMMENT 'NULL - на модерации, 1 - одобрено, 0 - отклонено',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `advantages`, `disadvantages`, `comment`, `is_approved`, `created_at`, `updated_at`) VALUES
(4, 9, 2, 4, 'хочу отзыв', 'хочу отзыв', 'хочу отзыв', 1, '2025-12-23 19:51:03', '2025-12-23 19:51:13');

-- --------------------------------------------------------

--
-- Структура таблицы `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('pCkz7FdZjyCymUl8UTxUy8Ksly0thwtA5s2vqY3a', 2, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 YaBrowser/25.12.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidlRyNUF4U2dxcW1yTDV5ODl3VHF1RWdrZmRKeUFPMERUblBIbE9UVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1768847084);

-- --------------------------------------------------------

--
-- Структура таблицы `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '0' COMMENT 'Количество на складе',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `stocks`
--

INSERT INTO `stocks` (`id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 4, 11, '2025-12-23 19:56:18', '2026-01-06 00:37:31'),
(2, 5, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(3, 6, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(4, 7, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(5, 8, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(6, 9, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(7, 10, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(8, 11, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(9, 12, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(10, 13, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(11, 14, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(12, 15, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(13, 16, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(14, 17, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(15, 18, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(16, 19, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(17, 20, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(18, 21, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(19, 22, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(20, 23, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(21, 24, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(22, 25, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(23, 26, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18'),
(24, 27, 10, '2025-12-23 19:56:18', '2025-12-23 19:56:18');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Телефон пользователя',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `addresses` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Адреса доставки в формате JSON',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banned_at` timestamp NULL DEFAULT NULL COMMENT 'Дата блокировки пользователя',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `admin`, `addresses`, `remember_token`, `banned_at`, `created_at`, `updated_at`) VALUES
(1, 'why', 'whywhy@gmail.com', NULL, NULL, '$2y$12$nDBybMWi8uzl4nCSBwzhz.SL1rATSBJO2IeW6QSq8LGdq9I92cWiO', 0, NULL, NULL, NULL, '2025-12-23 17:06:34', '2025-12-23 20:13:55'),
(2, 'whysite', 'deeee@bk.ru', NULL, NULL, '$2y$12$WZby92t.HSjAqfPzIqk6pergWzx5SR/jwJHg6Dvibc.ZU.grtE72a', 1, '\"[\\\"\\\\u043c\\\\u043e\\\\u0441\\\\u043a\\\\u0432\\\\u0430, \\\\u043a\\\\u0438\\\\u0440\\\\u0438\\\\u0435\\\\u0448\\\\u043a\\\\u0435\\\\u043d\\\\u043e 5\\\\u0432\\\"]\"', NULL, NULL, '2025-12-23 18:08:36', '2025-12-23 18:26:32'),
(3, 'dahuyameff', 'fff@ff', NULL, NULL, '$2y$12$nxhpzwa7Xv/meH2.snWsjOpwq4vCJN0ajNIuE4DVgaAk26CHF.nIW', 0, NULL, NULL, NULL, '2025-12-23 20:21:17', '2025-12-24 07:20:19');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cart_items_user_id_product_id_unique` (`user_id`,`product_id`),
  ADD KEY `cart_items_product_id_foreign` (`product_id`),
  ADD KEY `cart_items_session_id_index` (`session_id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_is_active_index` (`is_active`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_is_active_index` (`is_active`);

--
-- Индексы таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_is_approved_index` (`is_approved`);

--
-- Индексы таблицы `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Индексы таблицы `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stocks_product_id_foreign` (`product_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT для таблицы `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
