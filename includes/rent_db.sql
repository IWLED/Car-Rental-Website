-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 21 مايو 2024 الساعة 19:56
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rent_db`
--

-- --------------------------------------------------------

--
-- بنية الجدول `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`) VALUES
(1, 'test', 'test@test.com', 'test');

-- --------------------------------------------------------

--
-- بنية الجدول `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `model` varchar(255) NOT NULL,
  `color` varchar(50) NOT NULL,
  `year` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `cars`
--

INSERT INTO `cars` (`id`, `company`, `model`, `color`, `year`, `price`, `image_path`, `quantity`) VALUES
(11, 'Honda', 'Acord', 'white', 2024, 150.00, 'uploads/Acord_white_2024.png', 0),
(12, 'Honda', 'Acord', 'Red', 2024, 200.00, 'uploads/Camry_2024_red.png', 0),
(13, 'هوندا', 'اكورد', 'ابيض', 2024, 150.00, 'uploads/Acord_white_2024.png', 4);

-- --------------------------------------------------------

--
-- بنية الجدول `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment_text` text DEFAULT NULL,
  `car_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `comment_text`, `car_id`) VALUES
(81, 24, 'test', 11),
(82, 24, 'test', 11),
(83, 24, 'test', 11),
(98, 19, 'تجربة', 11);

-- --------------------------------------------------------

--
-- بنية الجدول `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `car_id` int(11) DEFAULT NULL,
  `rental_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `pickup_city` varchar(255) NOT NULL,
  `delivery_city` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `car_id`, `rental_date`, `return_date`, `total_price`, `pickup_city`, `delivery_city`) VALUES
(37, 1, 11, '2024-05-10', '2024-05-20', 1500.00, 'الرياض', 'المدينة المنورة'),
(38, 23, 11, '2024-05-10', '2024-05-15', 750.00, 'الرياض', 'جدة'),
(39, 23, 12, '2024-05-18', '2024-05-19', 200.00, 'الرياض', 'جدة'),
(41, 19, 11, '2024-05-10', '2024-05-15', 750.00, 'الرياض', 'جدة'),
(42, 24, 11, '2024-04-30', '2024-05-31', 4650.00, 'الرياض', 'الرياض'),
(43, 24, 11, '2024-05-15', '2024-05-16', 150.00, 'الرياض', 'جدة'),
(44, 1, 13, '2024-05-10', '2024-05-15', 750.00, 'الرياض', 'جدة');

-- --------------------------------------------------------

--
-- بنية الجدول `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `report_text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `Username` varchar(200) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`Id`, `Username`, `Email`, `Password`) VALUES
(1, 'test', 'test@test', 'test'),
(19, 'Waleed Alharbi', 'w444led@gmail.com', '$2y$10$mmLofe0NYJHgEPCmhrB10.AX.I0LRku79AabXzQiNJSg5owSbVYpy'),
(20, 'Waleed Alharbi', '443229420@tvtc.edu.sa', '$2y$10$f5ZXsIbKyl/bz.dbydPixuhoBYSxkEPnthsToAngv90RP2VUj9YGK'),
(21, 'وليد حمد الحربي', 'test@a', '$2y$10$W3AxVeFB8lgEYUMsLLru7eShK75q0r6KPzUEvOkZy6Rcs.4GWK.Km'),
(22, 'test', 'test@w', '$2y$10$ES2Lk6ACziQ90LvMZ54Cq.htXsfIP1WpGAyXUJYhHupY11zYHNXA.'),
(23, 'وليد حمد الحربي', 'z.nucii@gmail.com', '$2y$10$3Lq3T/pR8KONxmL7mog8feymthLP5B9vhYJ38XqXyYjj9iV2zorou'),
(24, 'يوسف العسيري', 'yousef@gmail.com', '$2y$10$ZPm8yozASucPXztASzOsquk3xtfPSjuiGrbznt39kYXJXlv7TpBZ.'),
(25, 'test', 'test@t', '$2y$10$GnlnylHuzJouPGmyguEja.UQDaFhYvlxERcCnHhlx8RV2MUIPKRWm');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`);

--
-- قيود الجداول `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`);

--
-- قيود الجداول `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
