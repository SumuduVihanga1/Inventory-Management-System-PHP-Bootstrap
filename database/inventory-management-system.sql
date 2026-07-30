-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 29, 2026 at 05:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory-management-system`
--

-- --------------------------------------------------------

--
-- Table structure for table `action_log`
--

CREATE TABLE `action_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `added_value` text DEFAULT NULL,
  `action_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `action_log`
--

INSERT INTO `action_log` (`id`, `user_id`, `action_type`, `product_id`, `added_value`, `action_time`) VALUES
(9, 1, 'add_category', NULL, '{\"category_added\":\"Sopes\"}', '2025-05-29 11:00:23'),
(10, 2, 'add_category', NULL, '{\"category_added\":\"Sopes\"}', '2025-05-29 11:01:27'),
(11, 2, 'add_user', NULL, '{\"name\":\"Dulanjana\"}', '2025-05-29 11:03:30'),
(59, 2, 'update_category', NULL, '{\"old\":{\"categoryname\":\"<br \\/><b>Warning<\\/b>:  Undefined variable $product in <b>D:\\\\Xampp\\\\htdocs\\\\inv\\\\store_keeper_ui\\\\cupdate\",\"description\":\"sunlight,kohomba,rani\"},\"new\":{\"categoryname\":\"<br \\/><b>Warning<\\/b>:  Undefined variable $product in <b>D:\\\\\\\\Xampp\\\\\\\\htdocs\\\\\\\\inv\\\\\\\\store_keeper_ui\\\\\\\\cupdate.php<\\/b> on line <b>86<\\/b><br \\/><br \\/><b>Warning<\\/b>:  Trying to access array offset on value of type null in <b>D:\\\\\\\\Xampp\\\\\\\\htdocs\\\\\\\\inv\\\\\\\\store_keeper_ui\\\\\\\\cupdate.php<\\/b> on line <b>86<\\/b><br \\/>\",\"description\":\"sunlight,kohomba,rani\"}}', '2025-05-29 16:54:05'),
(61, 2, 'add_category', NULL, '{\"category_added\":\"Sopes\"}', '2025-05-29 16:55:39'),
(62, 2, 'update_category', NULL, '{\"old\":{\"categoryname\":\"Sopes\",\"description\":\"sunlight,kohomba,rani\"},\"new\":{\"categoryname\":\"Sopes\",\"description\":\"sunlight,kohomba,rani,velvet\"}}', '2025-05-29 16:55:49'),
(64, 2, 'add_category', NULL, '{\"category_added\":\"Sopes\"}', '2025-05-29 16:57:57'),
(65, 2, 'update_category', NULL, '{\"old\":{\"categoryname\":\"Sopes\",\"description\":\"sunlight,kohomba,rani\"},\"new\":{\"categoryname\":\"Sopes\",\"description\":\"sunlight,kohomba,rani,velvet\"}}', '2025-05-29 16:58:06'),
(67, 2, 'add_category', NULL, '{\"category_added\":\"Sopes\"}', '2025-05-29 17:00:16'),
(69, 2, 'add_category', NULL, '{\"category_added\":\"Sopes\"}', '2025-05-29 17:02:17'),
(72, 2, 'add_category', NULL, '{\"category_added\":\"Sopes\"}', '2025-05-29 17:04:38'),
(74, 2, 'add_category', NULL, '{\"category_added\":\"Sopes\"}', '2025-05-29 17:05:54'),
(75, 2, 'delete_category', NULL, '{\"category_name\":\"Sopes\"}', '2025-05-29 17:06:05'),
(80, 2, 'add_category', NULL, '{\"category_added\":\"\"}', '2025-05-29 17:20:37'),
(81, 2, 'add_category', NULL, '{\"category_added\":\"Sopes\"}', '2025-05-29 17:22:37'),
(82, 2, 'add_category', NULL, '{\"category_added\":\"Sopes\"}', '2025-05-29 17:25:21'),
(83, 2, 'add_category', NULL, '{\"category_added\":\"Drinks\"}', '2025-06-06 10:53:47'),
(88, 2, 'add_category', NULL, '{\"category_added\":\"Drinks\"}', '2025-06-06 11:03:26'),
(89, 2, 'update_category', NULL, '{\"old\":{\"name\":\"Drinks\",\"description\":\"coca cola,pepsi,cream soda, etc...\"},\"new\":{\"name\":\"Drinks\",\"description\":\"coca cola,pepsi,cream soda,egb, etc...\"}}', '2025-06-06 11:17:49'),
(99, 1, 'add_user', NULL, '{\"name\":\"Hettipola\"}', '2025-06-06 12:08:15'),
(100, 1, 'add_user', NULL, '{\"name\":\"Hettipola\"}', '2025-06-06 12:09:08'),
(102, 1, 'add_user', NULL, '{\"name\":\"Hettipola\"}', '2025-06-06 12:14:23'),
(103, 1, 'delete_user', NULL, '{\"deleted_username\":\"Hettipola\"}', '2025-06-06 12:14:25'),
(107, 2, 'add_category', NULL, '{\"category_added\":\"drinks\"}', '2025-06-06 12:16:06'),
(108, 2, 'update_category', NULL, '{\"old\":{\"name\":\"drinks\",\"description\":\"soda\"},\"new\":{\"name\":\"drinks\",\"description\":\"soda coca pespi\"}}', '2025-06-06 12:16:22'),
(109, 12, 'add_category', NULL, '{\"category_added\":\"Baby Dipers\"}', '2025-06-19 10:33:52'),
(117, 1, 'add_user', NULL, '{\"name\":\"Kumara\"}', '2025-06-19 11:15:34'),
(118, 2, 'add_category', NULL, '{\"category_added\":\"Baby dipers\"}', '2025-06-19 11:16:10'),
(127, 2, 'add_category', NULL, '{\"category_added\":\"\"}', '2026-07-29 05:43:29'),
(132, 1, 'add_user', NULL, '{\"name\":\"sss\"}', '2026-07-29 06:11:01'),
(133, 1, 'delete_user', NULL, '{\"deleted_username\":\"sss\"}', '2026-07-29 06:11:11'),
(136, 2, 'add_category', NULL, '{\"category_added\":\"\"}', '2026-07-29 06:13:23'),
(137, 2, 'update_category', NULL, '{\"old\":{\"name\":\"Fruits & Vegetables\",\"description\":\"Fresh produce including fruits, leafy greens, and root vegetables.\"},\"new\":{\"name\":\"Fruits & Vegetables\",\"description\":\"Fresh produce including fruits, leafy greens, and root vegetables.\"}}', '2026-07-29 06:17:07'),
(138, 2, 'update_category', NULL, '{\"old\":{\"name\":\"Baby dipers\",\"description\":\"S,M,L\"},\"new\":{\"name\":\"Baby diper\",\"description\":\"S,M,L\"}}', '2026-07-29 06:18:47'),
(139, 2, 'add_category', NULL, '{\"category_added\":\"ss\"}', '2026-07-29 06:19:09'),
(142, 2, 'update_category', NULL, '{\"old\":{\"name\":\"Baby diper\",\"description\":\"S,M,L\"},\"new\":{\"name\":\"Baby dipers\",\"description\":\"S,M,L\"}}', '2026-07-29 06:52:20'),
(145, 1, 'add_user', NULL, '{\"name\":\"sss\"}', '2026-07-29 07:04:30'),
(146, 1, 'delete_user', NULL, '{\"deleted_username\":\"sss\"}', '2026-07-29 07:04:49'),
(176, 2, 'update_product', 39, '{\"old\":{\"n\":\"Orange Juice\",\"$\":\"350.00\",\"qty\":\"45\",\"status\":\"In Stock\"},\"new\":{\"n\":\"Orange Juice\",\"$\":350,\"qty\":45,\"status\":\"In Stock\"}}', '2026-07-29 08:37:27'),
(177, 2, 'update_product', 40, '{\"old\":{\"n\":\"Chocolate Bar\",\"$\":\"180.00\",\"qty\":\"35\",\"status\":\"In Stock\"},\"new\":{\"n\":\"Chocolate Bar\",\"$\":180,\"qty\":35,\"status\":\"In Stock\"}}', '2026-07-29 08:37:34'),
(178, 2, 'update_product', 39, '{\"old\":{\"n\":\"Orange Juice\",\"$\":\"350.00\",\"qty\":\"45\",\"status\":\"In Stock\"},\"new\":{\"n\":\"Orange Juice\",\"$\":350,\"qty\":55,\"status\":\"In Stock\"}}', '2026-07-29 08:38:05'),
(179, 2, 'update_product', 40, '{\"old\":{\"n\":\"Chocolate Bar\",\"$\":\"180.00\",\"qty\":\"35\",\"status\":\"In Stock\"},\"new\":{\"n\":\"Chocolate Bar\",\"$\":180,\"qty\":55,\"status\":\"In Stock\"}}', '2026-07-29 08:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(100) NOT NULL,
  `categoryname` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `categoryname`, `description`) VALUES
(1, 'Fruits & Vegetables', 'Fresh produce including fruits, leafy greens, and root vegetables.'),
(2, 'Dairy & Eggs', 'Milk, cheese, butter, yogurt, and eggs.'),
(3, 'Bakery', 'Bread, buns, cakes, cookies, and pastries.'),
(4, 'Meat & Seafood', 'Fresh and frozen meat, poultry, and seafood items.'),
(5, 'Beverages', 'uices, soft drinks, energy drinks, tea, coffee, and bottled water.'),
(6, 'Household Supplies', 'Cleaning products, paper towels, detergents, and trash bags.'),
(37, 'Baby dipers', 'S,M,L');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `expiredate` date NOT NULL,
  `category_id` varchar(50) NOT NULL,
  `status` varchar(100) DEFAULT 'In Stock',
  `last_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `quantity`, `expiredate`, `category_id`, `status`, `last_updated`) VALUES
(34, 'Rice 5kg', 1250.00, 80, '2027-01-15', '3', 'In Stock', '2026-07-29 08:30:10'),
(35, 'Sugar 1kg', 320.00, 25, '2027-05-20', '3', 'Low Stock', '2026-07-29 08:30:20'),
(36, 'Wheat Flour 1kg', 250.00, 60, '2027-03-10', '3', 'In Stock', '2026-07-29 08:30:30'),
(37, 'Chicken Meat 1kg', 950.00, 15, '2026-08-15', '4', 'Low Stock', '2026-07-29 08:31:00'),
(38, 'Fish 1kg', 1100.00, 0, '2026-08-05', '4', 'Out of Stock', '2026-07-29 08:31:10'),
(39, 'Orange Juice', 350.00, 45, '2027-02-28', '5', 'Low Stock', '2026-07-29 08:38:28'),
(40, 'Chocolate Bar', 180.00, 45, '2027-09-30', '5', 'Low Stock', '2026-07-29 08:38:38'),
(41, 'Shampoo 400ml', 650.00, 20, '2028-01-10', '6', 'Low Stock', '2026-07-29 08:31:40'),
(42, 'Toothpaste', 280.00, 55, '2028-04-15', '6', 'In Stock', '2026-07-29 08:31:50'),
(43, 'Soap Pack', 450.00, 100, '2028-06-30', '6', 'In Stock', '2026-07-29 08:32:00'),
(44, 'Potatoes', 180.00, 70, '2026-12-20', '1', 'In Stock', '2026-07-29 08:32:10'),
(45, 'Bananas', 220.00, 10, '2026-08-05', '1', 'Low Stock', '2026-07-29 08:32:20'),
(46, 'Cheese 500g', 850.00, 5, '2026-08-01', '2', 'Low Stock', '2026-07-29 08:32:30'),
(47, 'Butter 250g', 400.00, 0, '2026-09-15', '2', 'Out of Stock', '2026-07-29 08:32:40'),
(48, 'Green Tea Pack', 600.00, 75, '2027-11-25', '5', 'In Stock', '2026-07-29 08:32:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` enum('admin','store_keeper') NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `status` enum('active','deactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `last_login`, `status`) VALUES
(1, 'admin', 'sumudu1@gmail.com', '12345', 'admin', '2026-07-29 08:44:37', 'active'),
(2, 'sumudu', 'manakal@gmail.com', '12345', 'store_keeper', '2026-07-29 08:45:53', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `action_log`
--
ALTER TABLE `action_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `action_log_ibfk_2` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `action_log`
--
ALTER TABLE `action_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `action_log`
--
ALTER TABLE `action_log`
  ADD CONSTRAINT `action_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `action_log_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
