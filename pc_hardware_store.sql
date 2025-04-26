-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2025 at 01:35 PM
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
-- Database: `pc_hardware_store`
--
CREATE DATABASE IF NOT EXISTS `pc_hardware_store` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `pc_hardware_store`;

-- --------------------------------------------------------

--
-- Table structure for table `cancellations`
--

CREATE TABLE `cancellations` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `cancelled_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `created_at`) VALUES
(1, 1, 1721.99, 'pending', '2025-04-23 20:57:56');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 5, 1, 1599.99);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `socket_type` varchar(50) DEFAULT NULL,
  `ram_type` varchar(50) DEFAULT NULL,
  `wattage` int(11) DEFAULT NULL,
  `form_factor` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `refresh_rate` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category`, `stock`, `socket_type`, `ram_type`, `wattage`, `form_factor`, `created_at`, `refresh_rate`) VALUES
(1, 'Intel Core i9-13900K', '16-Core, 24-Thread Unlocked Desktop Processor', 599.99, '1.webp', 'CPU', 6, 'LGA1700', NULL, NULL, NULL, '2025-04-03 13:45:37', NULL),
(2, 'AMD Ryzen 9 7950X', '16-Core, 32-Thread Unlocked Desktop Processor', 549.99, '2.webp', 'CPU', 5, 'AM4', NULL, NULL, NULL, '2025-04-03 13:45:37', NULL),
(3, 'Intel Core i5-12600K', '10-Core, 16-Thread Unlocked Desktop Processor', 289.99, '3.webp', 'CPU', 8, 'LGA1700', NULL, NULL, NULL, '2025-04-03 13:45:37', NULL),
(4, 'AMD Ryzen 5 5600X', '6-Core, 12-Thread Unlocked Desktop Processor', 199.99, '4.webp', 'CPU', 12, 'AM4', NULL, NULL, NULL, '2025-04-03 13:45:37', NULL),
(5, 'NVIDIA GeForce RTX 4090', '24GB GDDR6X Graphics Card', 1599.99, '5.webp', 'GPU', 2, NULL, NULL, 450, NULL, '2025-04-03 13:45:37', NULL),
(6, 'AMD Radeon RX 7900 XTX', '24GB GDDR6 Graphics Card', 999.99, '6.webp', 'GPU', 6, NULL, NULL, 350, NULL, '2025-04-03 13:45:37', NULL),
(7, 'NVIDIA GeForce RTX 3060', '12GB GDDR6 Graphics Card', 329.99, '7.webp', 'GPU', 15, NULL, NULL, 200, NULL, '2025-04-03 13:45:37', NULL),
(8, 'AMD Radeon RX 6600', '8GB GDDR6 Graphics Card', 249.99, '8.webp', 'GPU', 20, NULL, NULL, 160, NULL, '2025-04-03 13:45:37', NULL),
(9, 'Corsair Vengeance RGB 32GB', 'DDR5 5600MHz Memory Kit', 149.99, '9.webp', 'RAM', 17, NULL, 'DDR5', NULL, NULL, '2025-04-03 13:45:37', NULL),
(10, 'G.Skill Trident Z5 32GB', 'DDR5 6000MHz Memory Kit', 169.99, '10.webp', 'RAM', 15, NULL, 'DDR5', NULL, NULL, '2025-04-03 13:45:37', NULL),
(11, 'Corsair Vengeance LPX 16GB', 'DDR4 3200MHz Memory Kit', 79.99, '11.webp', 'RAM', 25, NULL, 'DDR4', NULL, NULL, '2025-04-03 13:45:37', NULL),
(12, 'Kingston Fury Beast 16GB', 'DDR4 3600MHz Memory Kit', 89.99, '12.webp', 'RAM', 30, NULL, 'DDR4', NULL, NULL, '2025-04-03 13:45:37', NULL),
(13, 'Samsung 980 Pro 1TB', 'PCIe 4.0 NVMe SSD', 129.99, '13.webp', 'Storage', 22, NULL, NULL, NULL, NULL, '2025-04-03 13:45:37', NULL),
(14, 'WD Black SN850X 1TB', 'PCIe 4.0 NVMe SSD', 119.99, '14.webp', 'Storage', 30, NULL, NULL, NULL, NULL, '2025-04-03 13:45:37', NULL),
(15, 'Crucial MX500 1TB', 'SATA SSD', 89.99, '15.webp', 'Storage', 40, NULL, NULL, NULL, NULL, '2025-04-03 13:45:37', NULL),
(16, 'Seagate Barracuda 2TB', '7200 RPM HDD', 59.99, '16.webp', 'Storage', 50, NULL, NULL, NULL, NULL, '2025-04-03 13:45:37', NULL),
(17, 'ASUS ROG Strix Z790-E', 'LGA 1700 ATX Motherboard', 449.99, '17.webp', 'Motherboard', 2, 'LGA1700', 'DDR5', NULL, 'ATX', '2025-04-03 13:45:37', NULL),
(18, 'MSI MPG B550 Gaming Edge', 'AM4 ATX Motherboard', 179.99, '18.webp', 'Motherboard', 10, 'AM4', 'DDR4', NULL, 'ATX', '2025-04-03 13:45:37', NULL),
(19, 'Gigabyte Z690 Aorus Elite', 'LGA 1700 ATX Motherboard', 229.99, '19.webp', 'Motherboard', 8, 'LGA1700', 'DDR5', NULL, 'ATX', '2025-04-03 13:45:37', NULL),
(20, 'ASRock B450M Pro4', 'AM4 Micro-ATX Motherboard', 89.99, '20.webp', 'Motherboard', 15, 'AM4', 'DDR4', NULL, 'Micro-ATX', '2025-04-03 13:45:37', NULL),
(21, 'Corsair RM850x', '850W 80+ Gold Fully Modular PSU', 129.99, '21.webp', 'Power Supply', 12, NULL, NULL, 850, NULL, '2025-04-03 13:45:37', NULL),
(22, 'EVGA SuperNOVA 750 G6', '750W 80+ Gold Fully Modular', 109.99, '22.webp', 'Power Supply', 20, NULL, NULL, 750, NULL, '2025-04-03 13:45:37', NULL),
(23, 'Cooler Master MWE 650', '650W 80+ Bronze PSU', 79.99, '23.webp', 'Power Supply', 25, NULL, NULL, 650, NULL, '2025-04-03 13:45:37', NULL),
(24, 'Thermaltake Smart 500W', '500W 80+ White PSU', 49.99, '24.webp', 'Power Supply', 30, NULL, NULL, 500, NULL, '2025-04-03 13:45:37', NULL),
(25, 'Noctua NH-D15', 'Premium Dual-Tower Air Cooler', 89.95, '25.webp', 'CPU Cooler', 10, 'AM4,AM5,LGA1700,LGA1200', NULL, NULL, NULL, '2025-04-03 13:45:37', NULL),
(26, 'Corsair iCUE H150i', '360mm RGB Liquid CPU Cooler', 169.99, '26.webp', 'CPU Cooler', 5, 'AM4,AM5,LGA1700,LGA1200', NULL, NULL, NULL, '2025-04-03 13:45:37', NULL),
(27, 'Fractal Design Meshify C', 'ATX Mid Tower Case', 89.99, '27.webp', 'Case', 7, NULL, NULL, NULL, 'ATX', '2025-04-03 13:45:37', NULL),
(28, 'NZXT H510 Elite', 'Mid-Tower ATX Case with Tempered Glass', 149.99, '28.webp', 'Case', 5, NULL, NULL, NULL, 'ATX', '2025-04-03 13:45:37', NULL),
(29, 'Cooler Master MasterBox Q300L', 'Micro-ATX Case', 49.99, '29.webp', 'Case', 20, NULL, NULL, NULL, 'Micro-ATX', '2025-04-03 13:45:37', NULL),
(30, 'Thermaltake Versa H18', 'Micro-ATX Case', 39.99, '30.webp', 'Case', 25, NULL, NULL, NULL, 'Micro-ATX', '2025-04-03 13:45:37', NULL),
(31, 'be quiet! Dark Rock Pro 4', 'High-Performance Silent CPU Cooler', 89.90, 'cooler1.webp', 'CPU Cooler', 15, 'AM4,AM5,LGA1700,LGA1200', NULL, NULL, NULL, '2025-04-25 11:08:58', NULL),
(32, 'Cooler Master Hyper 212', 'RGB Black Edition CPU Air Cooler', 44.99, 'cooler2.webp', 'CPU Cooler', 25, 'AM4,AM5,LGA1700,LGA1200', NULL, NULL, NULL, '2025-04-25 11:08:58', NULL),
(33, 'ARCTIC Liquid Freezer II 240', '240mm AIO RGB CPU Liquid Cooler', 119.99, 'cooler3.webp', 'CPU Cooler', 12, 'AM4,AM5,LGA1700,LGA1200', NULL, NULL, NULL, '2025-04-25 11:08:58', NULL),
(34, 'ASUS TUF Gaming VG27AQ', '27\" 1440p 165Hz IPS Gaming Monitor', 349.99, 'monitor1.webp', 'Monitor', 10, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', 60),
(35, 'LG 27GP850-B', '27\" Ultragear 1440p 180Hz Gaming Monitor', 399.99, 'monitor2.webp', 'Monitor', 8, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', 60),
(36, 'Dell S2721DGF', '27\" 1440p 165Hz Gaming Monitor', 379.99, 'monitor3.webp', 'Monitor', 12, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', 60),
(37, 'Samsung Odyssey G7', '32\" 1440p 240Hz Curved Gaming Monitor', 599.99, 'monitor4.webp', 'Monitor', 5, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', 60),
(38, 'LG 34WN80C-B', '34\" UltraWide 1440p Curved Monitor', 549.99, 'monitor5.webp', 'Monitor', 7, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', 60),
(39, 'Logitech G Pro X', 'Mechanical Gaming Keyboard with RGB', 129.99, 'keyboard1.webp', 'Peripherals', 15, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', NULL),
(40, 'Corsair K70 RGB MK.2', 'Mechanical Gaming Keyboard with Cherry MX Red', 139.99, 'keyboard2.webp', 'Peripherals', 12, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', NULL),
(41, 'Logitech G Pro Wireless', 'Superlight Wireless Gaming Mouse', 129.99, 'mouse1.webp', 'Peripherals', 20, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', NULL),
(42, 'Razer DeathAdder V2', 'Wired Gaming Mouse with 20K DPI', 69.99, 'mouse2.webp', 'Peripherals', 25, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', NULL),
(43, 'SteelSeries Arctis 7', 'Wireless Gaming Headset with 24hr Battery', 149.99, 'headset1.webp', 'Peripherals', 10, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', NULL),
(44, 'HyperX Cloud II', 'Wired Gaming Headset with 7.1 Surround Sound', 99.99, 'headset2.webp', 'Peripherals', 18, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', NULL),
(45, 'ASUS RT-AX86U', 'AX5700 Dual Band WiFi 6 Gaming Router', 249.99, 'router1.webp', 'Networking', 8, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', NULL),
(46, 'TP-Link Archer AX50', 'AX3000 Dual Band WiFi 6 Router', 149.99, 'router2.webp', 'Networking', 15, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', NULL),
(47, 'Netgear Nighthawk XR1000', 'WiFi 6 Gaming Router', 299.99, 'router3.webp', 'Networking', 6, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', NULL),
(48, 'TP-Link TL-SG108', '8-Port Gigabit Ethernet Switch', 24.99, 'switch1.webp', 'Networking', 30, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', NULL),
(49, 'Intel Wi-Fi 6 AX200', 'PCIe WiFi 6 Card for Desktop', 39.99, 'wifi1.webp', 'Networking', 22, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', NULL),
(50, 'CAT8 Ethernet Cable', '10ft Shielded High-Speed Network Cable', 14.99, 'cable1.webp', 'Networking', 45, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', NULL),
(51, 'Corsair MM300', 'Extended Anti-Fray Cloth Gaming Mouse Pad', 29.99, 'mousepad1.webp', 'Accessories', 40, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', NULL),
(52, 'Razer Goliathus Extended', 'Soft Gaming Mouse Mat with RGB', 59.99, 'mousepad2.webp', 'Accessories', 25, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', NULL),
(53, 'VIVO Single Monitor Arm', 'Adjustable Desk Mount', 29.99, 'arm1.webp', 'Accessories', 15, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', NULL),
(54, 'Ergotron HX', 'Premium Monitor Arm', 109.99, 'arm2.webp', 'Accessories', 8, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', NULL),
(55, 'CableMod Pro Kit', 'Custom PSU Cable Kit (Black/Red)', 99.99, 'cables1.webp', 'Accessories', 12, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', NULL),
(56, 'Corsair iCUE RGB LED Strip', 'Magnetic RGB LED Lighting Strip', 39.99, 'led1.webp', 'Accessories', 20, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', NULL),
(57, 'Noctua NF-A12x25', '120mm Premium Quiet Cooling Fan', 29.99, 'fan1.webp', 'Accessories', 30, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', NULL),
(58, 'ARCTIC P12 PWM PST Value Pack', '5-Pack 120mm Case Fans', 34.99, 'fan2.webp', 'Accessories', 15, NULL, NULL, NULL, NULL, '2025-04-25 11:20:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `schema_version`
--

CREATE TABLE `schema_version` (
  `id` int(11) NOT NULL,
  `version` varchar(20) NOT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schema_version`
--

INSERT INTO `schema_version` (`id`, `version`, `applied_at`, `description`) VALUES
(1, '1.1', '2025-04-21 08:36:47', 'Initial database schema with users role and active columns');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_addresses`
--

CREATE TABLE `shipping_addresses` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping_addresses`
--

INSERT INTO `shipping_addresses` (`id`, `order_id`, `first_name`, `last_name`, `address`, `city`, `state`, `zip`) VALUES
(1, 1, 'abi', 'idris', '5 juillet', 'reo', 'AZ', '40000'),
(2, 1, 'abi', 'idris', '5 juillet', 'reo', 'California', '40000');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('user','admin') DEFAULT 'user',
  `active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `role`, `active`) VALUES
(1, 'admin', 'abiidris26@gmail.com', '$2y$10$I5PlsEe83iTxwIEQvLDCqebJ5un/MBcOTpfxfnh/rOupDjzNxY1Sm', '2025-03-27 12:08:40', 'admin', 1),
(2, 'guest', 'abiidris06@gmail.com', '$2y$10$Ai.jfBhovLmQCC2PuQKJHewyu1puOKwVbjoTQwHht1F8xVkG7cTy2', '2025-03-28 13:54:10', 'user', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cancellations`
--
ALTER TABLE `cancellations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schema_version`
--
ALTER TABLE `schema_version`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cancellations`
--
ALTER TABLE `cancellations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `schema_version`
--
ALTER TABLE `schema_version`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cancellations`
--
ALTER TABLE `cancellations`
  ADD CONSTRAINT `cancellations_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD CONSTRAINT `shipping_addresses_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
--
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Table structure for table `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Table structure for table `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Table structure for table `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

--
-- Dumping data for table `pma__designer_settings`
--

INSERT INTO `pma__designer_settings` (`username`, `settings_data`) VALUES
('root', '{\"angular_direct\":\"direct\",\"snap_to_grid\":\"off\",\"relation_lines\":\"true\",\"full_screen\":\"off\"}');

-- --------------------------------------------------------

--
-- Table structure for table `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Table structure for table `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Table structure for table `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

--
-- Dumping data for table `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"pc_hardware_store\",\"table\":\"products\"},{\"db\":\"pc_hardware_store\",\"table\":\"users\"},{\"db\":\"pc_hardware_store\",\"table\":\"shipping_addresses\"},{\"db\":\"pc_hardware_store\",\"table\":\"orders\"},{\"db\":\"pc_hardware_store\",\"table\":\"order_items\"},{\"db\":\"pc_hardware_store\",\"table\":\"schema_version\"},{\"db\":\"pc_hardware_store\",\"table\":\"cancellations\"},{\"db\":\"sfecommerce\",\"table\":\"caracteristique_technique\"},{\"db\":\"sfecommerce\",\"table\":\"categorie\"},{\"db\":\"sfecommerce\",\"table\":\"marque\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Table structure for table `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Table structure for table `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Dumping data for table `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2025-04-25 11:35:49', '{\"Console\\/Mode\":\"collapse\",\"ThemeDefault\":\"original\"}');

-- --------------------------------------------------------

--
-- Table structure for table `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Table structure for table `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indexes for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indexes for table `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indexes for table `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indexes for table `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indexes for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indexes for table `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indexes for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indexes for table `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indexes for table `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indexes for table `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indexes for table `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indexes for table `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indexes for table `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Database: `sfecommerce`
--
CREATE DATABASE IF NOT EXISTS `sfecommerce` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sfecommerce`;

-- --------------------------------------------------------

--
-- Table structure for table `caracteristique_technique`
--

CREATE TABLE `caracteristique_technique` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `caracteristique_technique`
--

INSERT INTO `caracteristique_technique` (`id`, `nom`) VALUES
(3, 'Fréquence(s) Mémoire'),
(4, 'Format de carte mère'),
(5, 'Longueur max. carte graphique'),
(6, 'Support du processeur'),
(7, 'Nombre de barrettes'),
(8, 'Nombre de slots mémoire'),
(9, 'Type de connecteur(s) graphique'),
(10, 'Bus'),
(11, 'Nombre de connecteurs graphique'),
(12, 'Type de slots d\'extension'),
(13, 'Nombre de slots d\'extension'),
(14, 'Longueur'),
(15, 'Nombre de slots pour disque dur'),
(16, 'Connecteurs pour disques durs'),
(17, 'Interface avec l\'ordinateur'),
(18, 'Connecteurs'),
(19, 'Connecteur alimentation'),
(20, 'Type de Dalle'),
(21, 'Norme du clavier');

-- --------------------------------------------------------

--
-- Table structure for table `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `categorie_parent_id` int(11) DEFAULT NULL,
  `nom` varchar(100) NOT NULL,
  `description` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `accueil` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categorie`
--

INSERT INTO `categorie` (`id`, `categorie_parent_id`, `nom`, `description`, `image`, `accueil`) VALUES
(1, NULL, 'Ordinateurs', '<div>Dopez votre productivité ou améliorez votre jeu avec un ordinateur fixe ou portable parmi notre sélection. Pour un usage familial, un ordinateur multimédia fixe ou portable vous permettra de naviguer, regarder des films ou séries ou effectuer des tâches bureautiques courantes. Faisant la part belle aux cartes graphiques performantes et processeurs de dernière génération, nos PC fixes gaming et ordinateurs portables gamer vous apporteront toute la puissance et la fluidité nécessaires pour vous poncer les hits populaires et à venir.</div>', '01HNCT401EDPQT1QTNPW6QWDD7.webp', 1),
(2, NULL, 'Composants', '<div>Nous vous proposons un large choix de composants PC pour faire évoluer votre PC fixe actuel ou monter un nouvel ordinateur. Epine dorsale du PC, la carte mère se fait greffer des composants clé comme la carte graphique et le processeur, auxquels s\'ajoutent les barrettes mémoire, l\'alimentation, la carte son ainsi que les éléments de stockage comme le SSD et le disque dur. Conçu dans une variété de designs, le boîtier PC vous permettra d\'abriter cet ensemble hardware. Du composant informatique pas cher au composant haut de gamme, nous vous proposons un très large choix de pièces détachées pour la mise à jour de votre ordinateur ou l\'assemblage de votre nouvelle configuration PC.</div>', '01HNCT4EEHRVHM8XTPEZ8YNDK7.webp', 1),
(3, NULL, 'Périphériques', '<div>Nos spécialistes ont sélectionné pour vous un vaste choix de périphériques informatiques. Ecran, clavier, souris et enceintes PC ou encore imprimante pour exploiter au mieux votre ordinateur, nous vous invitons à découvrir tous nos produits. Volant et manette de jeux pour les gamers, onduleur et prise parafoudre pour protéger votre matériel informatique, tablette graphique pour les créatifs, webcam pour la communication, télécommande multimédia pour remplacer vos nombreuses télécommandes.</div>', '01HNCT4PCC7J7YYXEJBJEGYPZZ.webp', 1),
(4, 2, 'Cartes graphique', '<div>Retrouvez sur Materiel.net une large sélection des <strong>meilleures cartes graphiques</strong>, équipées de GPU NVIDIA et AMD. Que vous soyez gamer ou professionnel de l\'image,&nbsp; les <strong>cartes vidéo de dernière génération</strong> sont incontournables pour profiter pleinement de vos applications et des jeux les plus récents, plonger dans la réalité virtuelle ou exploiter tout le potentiel des outils de graphisme et de modélisation 3D. Elles sauront délivrer la puissance de calcul nécessaire à produire des images d\'un réalisme à couper le souffle.</div>', '01HNCT53RDET6E09DR0A1FRVMW.webp', 1),
(5, 2, 'Cartes mère', '<div>Exploitez tout le potentiel de vos composants PC avec une <strong>carte mère</strong> de dernière génération parmi notre sélection. La carte mère est l\'épine dorsale de votre ordinateur de bureau : c\'est sur cet élément essentiel que vont venir se greffer le processeur, la carte graphique, la RAM et le SSD entre autres. Que vous cherchiez à assembler un PC fixe gaming ou multimédia, choisissez votre <strong>carte mère gamer ou entrée de gamme</strong> parmi des marques phares, dont Asus, MSI et Gigabyte. Sélectionnez un format adapté à votre boîtier PC : les <strong>cartes mères mini-ITX et micro-ATX</strong> seront idéales pour des petites tours, le format ATX conviendra à des boitiers moyen et grand tour. N\'oubliez pas de prendre en compte le type de socket (Intel / AMD) accueillant le processeur, son évolutivité et le nombre de ports disponibles USB, SATA ou PCI-Express. Pour économiser sur des ensembles de composants, n\'hésitez pas à opter pour nos kits d\'évolution PC.</div>', '01HNCT5BADB3V1SP80AK9NTK6V.webp', 1),
(6, 2, 'Processeurs', '<div>Améliorez les performances de votre ordinateur de bureau avec un <strong>processeur</strong> de dernière génération parmi notre sélection&nbsp; dont les gammes Core i5 et Core i7 pour carte mère au socket Intel, et les processeurs Ryzen 5 et Ryzen 7 pour carte mère au socket AMD. Véritable cerveau de votre ordinateur, le <strong>microprocesseur</strong> (ou <strong>CPU</strong> pour Central Processing Unit) joue une part importante dans la rapidité d\'exécution de votre PC. Vous souhaitez monter un PC fixe gamer ou simplement faire évoluer votre ordinateur actuel ? Classés en fonction de leur socket, les <strong>processeurs</strong> proposés contiennent toutes les informations sur les <strong>chipsets</strong> et les principales caractéristiques techniques dont le nombre de cœurs, la fréquence en GHz, la mémoire cache et la consommation. Couplé à une carte graphique GeForce RTX ou Radeon RX, le <strong>processeur PC</strong> moyen / haut de gamme vous permettra de vivre des expériences gaming optimales en mode HIGH et ULTRA ou de créer du contenu vidéo de haute qualité sans vaciller.</div>', '01HNCT7NS2M83DAB83X73M8YAT.webp', 1),
(7, 2, 'Boîtiers', '<div>Assemblez votre ordinateur sur mesure avec un <strong>boîtier PC</strong> parmi notre sélection, dont la gamme de boitiers Corsair et les boitiers Cooler Master. Premier contact visuel avec le PC, le <strong>design du boitier</strong> se décline sous plusieurs divers formats : le mini boitier PC, le boitier ATX (moyen tour) ou encore le grand boitier (E-ATX). Equipant souvent un PC gamer haut de gamme ou un ordinateur gaming petit prix, les <strong>boitiers PC</strong> fenêtrés (ou vitrés) vous permettront d\'admirer l\'ensemble de vos composants, et de jour comme de nuit avec les boitiers à LED RGB. Avec ou sans alimentation PC, compatible watercooling, le boîtier gamer ou bureautique intégrera parfaitement votre décor tout en assurant la bonne aération de votre ordinateur.</div>', '01HNCT863DNJN36B1KKKEMF7A6.webp', 0),
(8, 2, 'Refroidissement', '<div>Un composant au frais est un composant heureux ! Notre sélection de radiateurs et de ventilateurs offre à vos précieux processeurs et cartes graphiques les températures idéales pour s\'exprimer à leur plein potentiel. Si vous désirez profiter d\'un PC sans bruit, achetez un radiateur silencieux pour votre carte vidéo et choisissez pour votre processeur un bon ventirad CPU associé à une bonne pâte thermique. Nous avons sélectionné pour vous les meilleurs <strong>systèmes de refroidissement</strong> parmi les plus grandes marques (Cooler Master, Thermalright, Noctua, Scythe, Arctic Cooling et Prolimatech). Pour une <strong>machine bien tunée et bien refroidie</strong>, profitez également de nos radiateurs disques durs, radiateurs mémoires, et <strong>ventilateurs supplémentaires</strong>.</div>', '01HNCT8EQ7D3HJQBTD81F6JM5Y.webp', 0),
(9, 8, 'Refroidissement Processeur', '<div>Nous vous présentons le meilleur du <strong>refroidissement processeur</strong> afin de compléter votre configuration PC. Pour améliorer la dissipation thermique, plusieurs systèmes s\'offrent à vous. Le watercooling AIO privilégie un système de refroidissement fermé par eau. À l\'inverse, le ventirad n\'opère pas de refroidissement liquide mais utilise un radiateur et l\'air pour évacuer la chaleur. Cooler Master, Be Quiet!, Noctua, choisissez votre <strong>radiateur pour processeur</strong> parmi une large sélection de marques, et optimisez le refroidissement de votre PC.</div>', '01HNCT8NE2Q2T76VHH1ZHC9G8A.webp', 0),
(10, 2, 'Mémoire', '<div>Avec une barrette de <strong>RAM, ou mémoire PC</strong>, donnez un bon coup d\'accélérateur à votre ordinateur de bureau, votre Mac ou votre PC portable. Les logiciels et jeux les plus récents requièrent toujours plus de <strong>mémoire vive</strong>. Grâce à une gravure toujours plus fine sur les mémoires, chaque nouveau standard de RAM propose des taux de transfert et des fréquences toujours plus élevés, ainsi que des tensions plus basses (jusqu\'à 1.1V pour la DDR5). Pour éviter les mauvaises surprises, vérifiez que la génération de RAM est compatible avec la carte mère de votre PC. Au format DIMM pour les ordinateurs fixes et SO-DIMM pour les portables, augmentez la taille de la mémoire en l\'équipant de barrettes de 8 Go, 16 Go voire 32 Go pour les PC gamer les plus puissants.</div>', '01HNCT8WGBMTWZYDD5XXJKQQ2E.webp', 1),
(11, 2, 'Stockage', '<div>Nous avons sélectionné pour vous le meilleur du <strong>disque dur</strong> pour équiper votre Ordinateur de bureau, votre PC portable ou votre Mac. Pour des performances époustouflantes en lecture comme en transfert de données, nous vous conseillons vivement d\'opter pour un <strong>disque SSD</strong> ou SSHD avec une mémoire flash. Pensez-y pour donner une nouvelle jeunesse à ordinateur ou un PC Portable lent et un peu vieillissant. Nous vous proposons également une large gamme de <strong>disque dur interne</strong> pour stocker vos données et <strong>disque dur externe</strong> pour le transport de toutes vos fichiers lors de vos déplacements. Vous pourrez choisir et comparer votre disque dur parmi les plus grandes marques : <strong>Western Digital</strong>, <strong>Seagate</strong>, <strong>Samsung</strong>, <strong>Crucial</strong>, <strong>OCZ</strong>, <strong>Intel</strong>, <strong>Kingston</strong> ou encore <strong>Toshiba</strong>.</div>', '01HNCT93ZGX2HJ3XH7P3M7X0CB.webp', 0),
(12, 2, 'Alimentations', '<div>Assemblez votre ordinateur de bureau avec une <strong>alimentation PC puissante</strong> parmi notre sélection, dont les alimentations Be Quiet, Seasonic et Corsair. Fournissant de l\'énergie à tous les composants de votre boitier PC, le <strong>bloc d\'alimentation</strong> produit une puissance électrique qui est à déterminer en fonction de votre usage. Une puissance standard de 550 watts / 650 watts conviendra à un PC bureautique et multimédia, tandis qu\'une <strong>alimentation PC gamer</strong> de 1000 watts enverra le courant électrique requis par des machines gourmandes en calcul et en traitement graphique. Au format ATX ou SFX suivant la carte mère ou le format de la tour, le <strong>boitier d\'alimentation</strong> est rythmé par des innovations régulières en matière de rendement énergétique et de refroidissement. Incontournable en matière d\'alimentation PC, la <strong>certification 80 PLUS</strong> permet de garantir que 80% de l\'énergie produite est envoyée à la machine.</div>', '01HNCT9BB5TT3YMQ7826V3YERC.webp', 1),
(13, 11, 'Disque dur interne', '<div>Retrouvez le meilleur du <strong>disque dur interne</strong> pour PC de bureau ou ordinateur portable. Deux formats sont disponibles : 3.5 pouces et 2.5 pouces. En fonction de vos besoins de stockage et de votre type de PC (bureautique, gamer, vidéo, graphisme), choisissez le <strong>disque dur</strong> en fonction de l\'interface adaptée : SATA ou SAS. Déclinés en différentes capacités - du disque dur 1 To / 2 To / 4 To à plus de 16 To - nos spécialistes sont à l\'écoute du marché pour vous proposer les <strong>disques durs</strong> dotés des dernières technologies parmi les plus grands constructeurs. Simple à installer et évolutif, tirez le meilleur parti de votre PC avec ce support de stockage. Très complémentaires, combinez votre <strong>HDD</strong> avec un SSD interne pour booster les performances de votre PC.</div>', '01HNCT9NBMVH7G4W0PB5C9M3KP.webp', 0),
(14, 8, 'Watercooling', '<div>Retrouvez le meilleur du <strong>watercooling</strong> sur pour refroidir efficacement votre <strong>ordinateur de bureau</strong>. Longtemps destiné à une poignée de gamers et passionnés du tuning PC, le <strong>watercooling (ou refroidissement liquide)</strong> est aujourd\'hui largement démocratisé. Adepte de <strong>watercooling custom</strong> ? Equipez-vous avec tous les éléments qui feront de votre système de refroidissement bien plus qu\'un système de <strong>watercooling AIO</strong> ou qu\'un <strong>ventirad</strong> classique. Des tubes au réservoir en passant par les waterblocks, <strong>Barrow</strong>, <strong>EkWaterBlock</strong> et bien d\'autres marques vous proposent l\'ensemble des pièces nécessaires au montage de votre <strong>watercooling complet</strong> ! Découvrez également les <strong>kits de watercooling</strong> performants, silencieux et adaptés à la majeure partie des configurations. Comptant parmi les meilleurs alliés de votre <strong>PC gamer fixe</strong>, n\'attendez plus pour refroidir votre CPU, votre carte graphique et votre chipset de <strong>carte mère</strong> !</div>', '01HNFGGSMKXQ2C4AXD50Z8T42Y.webp', 0),
(15, 3, 'Écrans', '<div>Soyez à la pointe des technologies d\'affichage avec notre gamme d\'écrans PC, allant des <strong>dalles dédiées au gaming</strong> aux <strong>moniteurs</strong> pour les graphistes. Plus qu\'un périphérique, l\'<strong>écran PC</strong> magnifie les performances d\'un processeur et d\'une carte graphique, en rendant chaque pixel plus vivant. Avec les dalles IPS et VA, offrez-vous des angles de vision impeccables. Osez la grandeur avec les diagonales d\'écran de 27\", 32\" voire l\'impressionnant 49\". Plongez dans une clarté exceptionnelle avec les <strong>résolutions élevées 4K</strong> et QHD. Le confort étant essentiel pour les sessions d\'activités longues, optez pour des écrans au pied réglable, à la dalle pivotable et inclinable, sans oublier les technologies anti-scintillement et anti-lumière bleue. Que ce soit pour vos jeux, vos projets créatifs ou vos films, n\'attendez plus pour donner vie à vos contenus multimédia.</div>', '01HP1V3Y7E43C6ACBKEQ750E6Q.webp', 1),
(16, 3, 'Clavier et souris', '<div>Améliorez votre jeu ou votre créativité avec un <strong>clavier</strong> et une <strong>souris</strong> parmi les derniers modèles référencés. Périphériques indispensables à votre ordinateur fixe ou mobile, les <strong>claviers / souris</strong> se déclinent pour différents usages. Clavier mécanique (à switchs) et souris RGB aux multiples boutons programmables seront des alliés de choix pour les gamers. Côté confort d\'utilisation, les souris ambidextres et les claviers ergonomiques vous apaiseront lors de longues journées de télétravail ou au bureau. Permettant d\'évaluer la précision et la vitesse du capteur de la souris, le nombre de DPI (Dots Per Inch) sera l\'un des facteurs déterminants pour les graphistes et créateurs de contenu. Faites également des achats malins avec nos packs de <strong>clavier souris</strong> gaming et bureautiques optimisés, et tout particulièrement les <strong>claviers souris sans fil</strong> qui vous éviteront de multiplier les câbles sur votre zone de jeu ou de travail.</div>', '01HP1XMBX2P34K0TFDN350G4FZ.webp', 1),
(17, 16, 'Claviers', '<div>Boostez votre productivité ou votre jeu avec un <strong>clavier</strong> parmi les meilleurs modèles ! Formant un duo inséparable avec la souris, le <strong>clavier</strong> est un périphérique de saisie qui se raccorde en plug and play à un ordinateur. Dédiés à une utilisation gaming, les <strong>claviers mécaniques</strong> augmenteront votre rapidité et l\'efficacité de vos actions grâce à un large choix de switchs, sans compter sur le rétro-éclairage RGB qui sera du plus effet lors de vos sessions nocturnes. Brillant par leur format ultra compact, les <strong>mini claviers</strong> s\'adressent aux nomades et à ceux qui ont des contraintes d\'espace sur leur bureau. Pour vous affranchir des câbles, optez pour un <strong>clavier sans fil</strong> à la dernière norme Bluetooth. Si les <strong>claviers d\'ordinateurs</strong> bureautiques revêtent un design plus sobre que les <a href=\"https://www.materiel.net/clavier-pc/l479/+fv1025-5797/\"><strong>claviers gaming</strong></a>, ils n\'en sont pas moins performants avec leurs <strong>touches silencieuses</strong> et un confort de frappe étudiés pour le télétravail comme pour l\'open space.</div>', '01HP1XSR0P59TQZYK92V2FC0N6.webp', 0),
(18, 16, 'Souris', '<div>Optimisez vos sessions bureautiques ou gaming avec une <strong>souris informatique</strong> parmi notre sélection, dont les souris Logitech et Razer. Périphérique de pointage indispensable à votre <strong>PC de bureau</strong> ou <strong>ordinateur portable</strong>, la <strong>souris</strong> est le prolongement naturel de votre main et exécute toutes vos tâches à l\'aide des<strong> boutons</strong> et de la <strong>molette</strong>. Dédiée aux joueurs et plus réactive que la moyenne, la <strong>souris gamer</strong> possède la plupart du temps des boutons programmables et un nombre élevé de <strong>DPI</strong> pour améliorer la précision de vos actions, essentielle pour les FPS et les MMORPG. Luttant contre l\'inconfort, la <strong>souris ergonomique</strong>, en particulier la <strong>souris verticale</strong>, permet de restituer la position naturelle du poignet. La <strong>souris sans fil</strong> vous permettra d\'organiser votre espace sans les contraintes de câble. N\'attendez plus pour vous offrir une <strong>souris PC</strong> performante et adaptée à votre usage !</div>', '01HP1XVFQWZ62TE4XCYWAAW86X.webp', 0);

-- --------------------------------------------------------

--
-- Table structure for table `marque`
--

CREATE TABLE `marque` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `logo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marque`
--

INSERT INTO `marque` (`id`, `nom`, `logo`) VALUES
(1, 'Gigabyte', 'gigabyte.webp'),
(2, 'Asus', 'asus.webp'),
(3, 'Corsair', 'corsair.webp'),
(4, 'Intel', 'intel.webp'),
(5, 'Be Quiet', 'be-quiet.webp'),
(6, 'Seagate', 'seagate.webp'),
(7, 'MSI', 'msi.webp'),
(8, 'AMD', 'amd.webp'),
(9, 'Phanteks', 'C000035444.webp'),
(10, 'Noctua', 'noctua.webp'),
(11, 'Logitech', 'logitech.webp');

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
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

--
-- Dumping data for table `messenger_messages`
--

INSERT INTO `messenger_messages` (`id`, `body`, `headers`, `queue_name`, `created_at`, `available_at`, `delivered_at`) VALUES
(1, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:39:\\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\\":4:{i:0;s:41:\\\"registration/confirmation_email.html.twig\\\";i:1;N;i:2;a:3:{s:9:\\\"signedUrl\\\";s:178:\\\"http://127.0.0.1:8000/verify/email?expires=1700004278&id=1&signature=%2FkUAuBvbuKEsI8plIQn%2FFIUWMM%2FNlWOA93j8xo%2BjjBE%3D&token=dvwQXPt8hQo8%2F9XFUNf2L7hhhUKbFuvYMhB1Y7s117Y%3D\\\";s:19:\\\"expiresAtMessageKey\\\";s:26:\\\"%count% hour|%count% hours\\\";s:20:\\\"expiresAtMessageData\\\";a:1:{s:7:\\\"%count%\\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:20:\\\"admin@e-commerce.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:16:\\\"Admin E-Commerce\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:22:\\\"cedric.falda@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:25:\\\"Please Confirm your Email\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2023-11-14 22:24:38', '2023-11-14 22:24:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `produit`
--

CREATE TABLE `produit` (
  `id` int(11) NOT NULL,
  `marque_id` int(11) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `categorie_principale_id` int(11) NOT NULL,
  `date_lancement` datetime NOT NULL,
  `designation` varchar(150) NOT NULL,
  `resume` longtext NOT NULL,
  `descriptif` longtext NOT NULL,
  `photo` varchar(150) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `archive` tinyint(1) NOT NULL,
  `descriptif_detaille` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produit`
--

INSERT INTO `produit` (`id`, `marque_id`, `categorie_id`, `categorie_principale_id`, `date_lancement`, `designation`, `resume`, `descriptif`, `photo`, `prix`, `stock`, `archive`, `descriptif_detaille`) VALUES
(1, 1, 5, 2, '2023-10-21 00:00:00', 'Gigabyte B650 AORUS ELITE AX', 'AMD B650 - Socket AM5 - ATX - Raphael - Wi-Fi 6E - Compatible processeurs AMD Ryzen 7000', '<div>Conçue pour les gamers, la carte mère Gigabyte <strong>B650 AORUS ELITE AX</strong> vous permettra de profiter des performances des processeurs AMD Ryzen série 7000 sur socket AM5. Elle prend en charge la mémoire DDR5 ainsi que la norme PCIe 5.0 pour une bande passante améliorée, ainsi qu\'une connectique plus fournie ! Il ne vous reste plus qu\'à créer la configuration PC de jeu de vos rêves.&nbsp;</div>', '01HNCXG5GMGMDS5HE2XX3J1AGG.webp', 75.95, 17, 0, '<div><strong>Plateforme AMD AM5 pour Ryzen série 7000<br></strong><br></div><div>La carte mère Gigabyte B650 AORUS ELITE AX fait partie des cartes mères socket AM5 qui accompagnent la micro-architecture AMD Zen 4. Et avec elle, son lot de nouvelles fonctionnalités comme le support de la mémoire DDR5. Concentrant l\'essentiel de la connectique et connectivité nécessaires à tous les usages, elle permet de profiter des performances des processeurs Ryzen 7000 facilement.<br><br></div><div>AMD inaugure également les profils étendus pour l\'overclocking avec sa technologie AMD EXPO, développée pour permettre une prise en charge intuitive de l\'overclocking de tous les types de mémoire et d\'accéder à des performances redoutables.<br><br></div><div>Tandis que le passage à une autre plateforme peut se traduire par de nombreux changements, AMD a garanti que les solutions de refroidissement AM4 existantes continuent à être prises en charge, assurant ainsi une transition fluide vers AM5.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:741,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/Composants%20PC/Processeurs/AMD/Ryzen%207000/800-ryzen7000-1.jpg&quot;,&quot;width&quot;:800}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/Composants%20PC/Processeurs/AMD/Ryzen%207000/800-ryzen7000-1.jpg\" width=\"800\" height=\"741\"><figcaption class=\"attachment__caption\"></figcaption></figure></div><div><strong>Une solution technique de premier choix<br></strong><br></div><div>Afin de suivre la montée en puissance occasionnée par les processeur, ce modèle intègre un dispositif de refroidissement efficace. La Gigabyte B650 AORUS Elite AX embarque donc un large radiateur MOSFET, des caloducs de 8 mm pour une dissipation optimale de la chaleur et une plaque de renfort permettant de réduire de 30% les températures des MOSFET.<br><br></div><div>Comptez aussi sur la technologie Smart Fan 6 pour un contrôle intelligent ainsi que des dissipateurs Thermal Guard pour les slots SSD M.2 pour tempérer efficacement les ardeurs de votre processeur Ryzen et assurer un fonctionnement optimale et atteindre les meilleures performances possibles.</div>'),
(2, 2, 5, 2, '2023-10-22 01:00:00', 'Test', 'Test', '<div>Descriptif</div>', '01HNCXGR733FH2ND6V75RD8JPP.webp', 50.00, 11, 1, NULL),
(3, 1, 4, 2, '2023-11-05 00:00:00', 'Gigabyte GeForce RTX 4060 WINDFORCE OC', 'GeForce RTX 4060, PCI-Express 4.0 8x, 8 Go GDDR6, DLSS 3', '<div>La carte graphique Gigabyte GeForce RTX 4060 WINDFORCE OC met à disposition toutes les performances de l\'architecture NVIDIA Ada Lovelace et la puissance combinée du ray-tracing et du DLSS3 pour vous permettre de créer votre configuration PC gamer, qui vous fera vibrer en 1080p dans les meilleures conditions !</div>', '01HNCXH9KC7W55XN40686VNRJG.webp', 369.95, 20, 0, NULL),
(4, 1, 5, 2, '2023-12-27 00:00:00', 'Test produit Gigabyte', 'Test', '<div>Descriptif</div>', '01HNCXHYG8XMS9GVPPQ1GE11M7.webp', 28.69, 3, 1, NULL),
(5, 3, 7, 2, '2024-01-09 00:00:00', 'Corsair 3000D Airflow - Noir', 'Moyenne tour, ATX / E-ATX / Micro ATX / Mini ITX, Noir', '<div>Le boitier Corsair 3000D Airflow est un boitier simple et performant qui permet d\'accueillir votre configuration de jeu et de bien la refroidir. Ce boitier moyen tour est doté d\'un panneau avant optimisé pour le passage de l\'air, et il est aussi équipé de 2 ventilateurs AF120.</div>', '01HNCXJ95T3H4AD17K2A40BM5C.webp', 89.95, 15, 0, NULL),
(6, 4, 6, 2, '2024-01-09 01:00:00', 'Intel Core i5 13600KF', '14 coeurs, 20 threads, 3.50 GHz, 24 Mo, Raptor Lake, BX8071513600KF', '<div>Le processeur Intel Core i5 13600KF débarque avec des fréquences boostées et un nombre de coeur en hausse par rapport à la génération précédente. Streaming, création, gaming : il sera à l\'aise en toutes circonstances grâce à l\'architecture hybride alliant P-Core et E-Core et surtout au support de la norme PCIe 5.0 et de la mémoire DDR5. Que vous soyez gamer ou créateur de contenu multimedia, la performance est au rendez-vous !</div>', '01HNCXJZZBX88QS5582MQDQ0KA.webp', 379.96, 20, 0, NULL),
(7, 5, 9, 2, '2024-01-09 02:00:00', 'Be Quiet Pure Rock Slim 2', 'Simple tour, Cuivre et aluminium, 1150 / 1151 / 1155 / 1200 / 1700 et AM4, AM5', '<div>La conception du Pure Rock Slim est compatible avec les plateformes Intel 1150 / 1151 / 1155 / 1200/1700 et AMD AM4, AM5 et convient particulièrement aux mini PC grâce à sa conception asymétrique et compacte. Si vous êtes à la recherche d\'un ventirad avec le meilleur rapport prix/performance, ne cherchez plus... Le Pure Rock Slim est le choix idéal !</div>', '01HNCXKFG448MWTD914V27PQ9Y.webp', 29.95, 30, 0, '<div><strong>Tout le savoir-faire Be Quiet !<br></strong><br></div><div><strong>Haute performance et faible nuisance sonore !<br></strong><br></div><div>Doté d\'une structure à ailettes en aluminium et de 3 caloducs de 6mm de diamètre, ce ventirad évacue la chaleur de façon optimale grâce a son ventilateur de 92 mm. Impossible de prendre le Pure Rock Slim à défaut au niveau de la dissipation thermique, en effet avec un seul ventilateur ce dernier offre déjà d\'excellentes performances de refroidissement avec une dissipation thermique TDP pouvant monter jusqu\'à <strong>130 W</strong>.<br><br></div><div>Grâce à un <strong>ventilateur de 92 mm</strong> monté sur ce radiateur, le Pure Rock Slim offre un débit d\'air excellent et un silence maximum. La conception exclusive de ses pales est optimisée pour délivrer un flux d\'air maximal même à faible vitesse. Il est également équipé de la technologie PWM permettant de réguler la vitesse de rotation en fonction des besoins. Le résultat est une installation très silencieuse, avec un volume sonore maximal de <strong>25,4 dBA</strong>.<br><br></div><div>Afin de ne pas faire de jaloux be quiet! à pensé à tout, et le ventirad Pure Rock Slim est compatible avec la quasi-totalité des sockets AMD actuels AM4, AM5 et Intel 1150 / 1151 / 1155 / 1200/1700.<br><br></div><div>Sûr de la qualité et de la fiabilité de son ventirad Pure Rock Slim, Be Quiet! propose sur celui-ci une <strong>garantie de 3 ans</strong>.</div>'),
(8, 3, 10, 2, '2024-01-09 03:00:00', 'Corsair Vengeance LPX Black DDR4 2 x 8 Go 3200 MHz CAS 16', 'RAM PC, DDR4, 16 Go, 3200 MHz - PC25600, 16-18-18-36, 1,35 Volts, CMK16GX4M2B3200C16', '<div>Corsair ne s\'est pas fait attendre avant de sortir ses premiers kits de mémoire DDR4 ! Découvrez la série Vengeance LPX qui vous offre plus de réactivité que la DDR3 ainsi que des performances excellentes sur le long terme, grâce notamment à une conception qui favorise le refroidissement du PCB.</div>', '01HNCXKTKWAQHHYSS7VNW43QE1.webp', 59.95, 50, 0, '<div><strong>Conçue pour de l\'overclocking haute performance<br></strong><br></div><div><strong>Un module DDR4 très avancé<br></strong><br></div><div>La <strong>mémoire DDR4</strong> <strong>Vengeance LPX</strong> de Corsair est conçue pour fonctionner avec les cartes mères basées sur l\'architecture <strong>Intel Skylake</strong> afin de tirer le maximum de ses possibilités. Les cartes mères à base de contrôleur principal Z170, H170 ou B150 sont basées sur cette architecture et les constructeurs de cartes mères comme Asus ou MSI ont d\'ores et déjà validé ces kits de mémoire.<br><br></div><div>Ce modèle profite d\'un <strong>design low-profile</strong> et intègre 2 barrettes de 8 Go, pour un total de <strong>16 Go</strong> de mémoire à <strong>3200 MHz</strong>. Grâce à leur <strong>dissipateur thermique intégré</strong> en aluminium et un PCB à <strong>8 couches</strong>, les <strong>Vengeance LPX</strong> assurent un refroidissement excellent du PCB (idéal pour les environnements exigeants et des performances durables !).<br><br></div><div>Enfin, la mémoire <strong>DDR4</strong> offre plus de réactivité et de bande passante par rapport à la DDR3 ainsi que des fréquences d\'utilisations plus étendues.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:300,&quot;url&quot;:&quot;https://media.materiel.net/oproducts/AR201508140026_d0.jpg&quot;,&quot;width&quot;:400}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/oproducts/AR201508140026_d0.jpg\" width=\"400\" height=\"300\"><figcaption class=\"attachment__caption\"></figcaption></figure></div><div><strong>Tout pour l\'overclocking<br></strong><br></div><div>La <strong>Vengeance LPX</strong> est compatible avec le <strong>standard XMP 2.0</strong> supporté par les cartes mères basées sur un chipset Intel X99. Enclenchez-le, et il s\'ajustera automatiquement à la vitesse adéquate. Il ne vous reste qu\'à profiter d\'une performance incroyable et fiable sans craindre pour vos données.</div>'),
(9, 6, 13, 2, '2024-01-09 04:00:00', 'Seagate BarraCuda - 2 To - 256 Mo', 'Disque dur 2 To, 3.5\", SATA/AHCI, 7200 tr/min, 256 Mo, ST2000DM008', '<div>Tirez le meilleur profit de votre stockage avec les disques durs BarraCuda de Seagate. Que vous souhaitiez conserver vos innombrables photos et souvenirs ou augmenter la capacité de votre PC de jeu, les disques BarraCuda évoluent avec vous.</div>', '01HNCXM565R6TNFDHYXQFNDD4V.webp', 72.95, 35, 0, NULL),
(10, 1, 12, 2, '2024-01-09 05:00:00', 'Gigabyte GP-P750GM - Gold', 'Alimentation PC 750W, Modulaire, 80 PLUS Or, 2 x CPU', '<div>L\'alimentation PC Gigabyte GP-P750GM est idéale pour votre PC gamer avec sa puissance de 750W. Cetifiée 80 PLUS Or, elle montre un niveau d\'efficacité remarquable, elle possède aussi un système de refroidissement interne performant et silencieux. Grâce aux nombreux connecteurs, et sa modularité allez à l\'essentiel !</div>', '01HNCXN5T6DA2QMSHNBXNPCERF.webp', 109.94, 15, 0, NULL),
(11, 7, 5, 2, '2024-01-10 00:00:00', 'MSI A520M A-PRO', 'Socket AM4, AMD A520, 1 port PCI-Express 16x, 3200 MHz, 1 port M.2 (SATA et PCIE), Micro-ATX', '<div>Les cartes mères MSI de série PRO sont pensées pour intégrer tous les PC. Elles sont conçues pour accueillir les processeurs AMD Ryzen et AMD Athlon sur socket AMD AM4 et pour offrir des performances fiables et des solutions professionnelles intelligentes qui vous rendront le travail plus facile. En résumé, la carte mère MSI A520M A-PRO est synonyme de stabilité, d\'efficacité et de longévité.</div>', '01HNCXNNB4K2NEJPVT00V2MMXY.webp', 75.95, 10, 0, '<div><strong>MSI A520M Pro : performances augmentées<br></strong><br></div><div>Pour les utilisateurs en recherche d\'un PC accessible, polyvalent pour travailler sans problème, le chipset AMD A520 se démarque comme la plateforme idéale tirer le meilleur des processeurs AMD Ryzen 3000 basé sur l\'architecture Zen 2 ainsi que les processeurs sur architecture Zen 3, ainsi de futures évolutions sont possible pour maintenir votre équipement à jour ! Fiable, stable, ces cartes mères disposent de l\'essentiel de connectivité et de bande passante pour satisfaire les besoins de tous les types d\'utilisateurs, que ce soit pour un usage au bureau ou à domicile.<br><br></div><div>La carte mère MSI A520M PRO se dote de nombreuses fonctionnalités qui la rendent idéale pour le montage d\'un PC bureautique/multimedia et à usage professionnel. Support de la mémoire DDR4 3200+, 4 ports Sata 6Gb/s, dissipateur M.2 Shield Frozr pour garder votre SSD M.2 à bonne température de fonctionnement : des caractéristiques qui lui assurent une stabilité et une fiabilité excellentes.<br><br></div><div>MSI a également doté sa carte mère d\'utilitaires logiciel pratiques : Click BIOS 5 pour une amélioration des performances, AUDIO Boost pour gérer ma qualité audio de manière optimale ou encore X-boost qui permet d\'augmenter les performances de stockage et des périphériques USB.</div><div>.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:300,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/carte%20m%C3%A8re/msi/500-prom2v2.jpg&quot;,&quot;width&quot;:500}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/composants%20pc/carte%20m%C3%A8re/msi/500-prom2v2.jpg\" width=\"500\" height=\"300\"><figcaption class=\"attachment__caption\"></figcaption></figure></div>'),
(12, 7, 5, 2, '2024-01-10 01:00:00', 'MSI PRO H610M-E DDR4', 'Intel H610 - Socket LGA1700 - Micro ATX - Alder Lake S - Compatible processeurs Intel Core 12ème génération - Support DDR4', '<div>La carte mère MSI PRO H610M-G DDR4 enrichit la série 600 sur socket LGA 1700 destinée aux processeurs Intel de 12ème génération. MSI propose sa série PRO, destinée aux configurations professionnelles et dotée de l\'essentiel en terme de connectique, connectivité et stockage. Le tout supporté par des composants fiables et robustes.</div>', '01HNCXP4G3EBC6W6V90NFFR9TH.webp', 95.95, 30, 0, '<div><strong>Chipset H610 : aux portes des processeurs Intel Alder Lake<br></strong><br></div><div>La MSI PRO H610M-E DDR4 fait partie des cartes mères de la <strong>série PRO</strong> qui aident les professionnels à améliorer leur productivité et leur efficacité grâce à des fonctionnalités intelligentes qui pourront les accompagner dans toutes leurs tâches. Leur composition est également à la hauteur des attentes des utilisateurs les plus exigeants et la qualité des composants utilisés est telle que ces cartes mères garantissent une durée de vie prolongée et des besoins de dépannages moindres.<br><br></div><div>Dans l\'optique d\'utiliser des processeurs intégrant de plus en plus en de coeurs, <strong>MSI</strong> a doté son modèle de l\'essentiel côté connectique t stockage : ports USB 3.0 pour les périphériques, slot M.2 pour votre SSD : fluidité et réactivité sont de mises pour vous accompagner aux quotidiens dans vos tâches professionnelles.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:420,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/carte%20m%C3%A8re/msi/h6xx/600-h610prod4-g.jpg&quot;,&quot;width&quot;:600}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/composants%20pc/carte%20m%C3%A8re/msi/h6xx/600-h610prod4-g.jpg\" width=\"600\" height=\"420\"><figcaption class=\"attachment__caption\"></figcaption></figure></div>'),
(13, 8, 6, 2, '2024-01-10 02:00:00', 'AMD Ryzen 7 7700X', 'Processeur 8 coeurs, 4.50 GHz, 32 Mo, AMD Zen 4, TDP 105 Watts, socket AM5, version boîte sans ventilateur', '<div>AMD passe à la vitesse \"Zen 4\" avec le processeur Ryzen 7 7700X. Pensés pour les gamers et les créateurs, les Ryzen Série 7000 offrent boost de fréquence et augmentation des performances en mono-thread pour permettre à votre configuration PC de s\'attaquer à n\'importe quel jeu ou workflow.</div>', '01HNCXPPACXZ7B2H3AT3YXAF7T.webp', 414.95, 20, 0, NULL),
(14, 7, 4, 2, '2024-01-26 00:00:00', 'MSI GeForce RTX 4090 Gaming X TRIO', 'GeForce RTX 4090, PCI-Express 16x, 24 Go GDDR6X, DLSS 3', '<div>Avec comme promesse une expérience gaming fluide jusqu\'en 8K, la carte graphique MSI GeForce RTX 4090 Gaming X TRIO déploie les performances d\'un monstre de puissance. Avec des capacités élevées à un niveau extrême grâce à l\'architecture NVIDIA Ada Lovelace et supportée par 24 Go de mémoire GDDR6X, elle sera une alliée de choix pour des sessions de jeu extrêmes ou pour la création de contenu vidéo.</div>', '01HNCX1DD9HWS443GX381EP26D.webp', 2429.95, 20, 0, '<div><strong>RTX 4090 pour jouer en 4K<br></strong><br></div><div>Avec l\'architecture Ada Lovelace, la carte graphique MSI GeForce RTX 4090 Gaming X TRIO inaugure un saut de performances excellent par rapport à la génération précédente. Coeurs Tensor de quatrième génération pour l\'Intelligence artificelle, coeurs RT de troisième génération pour des capacités de ray-tracing encore plus fines : tout est réuni pour une expérience de jeu à un niveau extrême !<br><br></div><div>Elle s\'appuie également sur pas moins de 24 de mémoire GDDR6X et 16 384 coeurs CUDA qui libéreront le plein potentiel de ce GPU de pointe. Des telles performances requiert un refroidissement particulièrement soigné, que la Gaming X Trio réussit avec succès. Les ventilateurs Torx 5.0 redirigent l\'air vers le système de refroidissement Tri Frozr 3 de dernière génération, épaulés par des caloducs Core Pipe méticuleusement placés sur le GPU pour maximiser la dissipation de chaleur, vous obtenez une carte bien régulée et capable de vous fournir un expérience de jeu des plus fluides.<br><br></div><div>Enfin, mettez un peu de lumière dans votre machine en personnalisant et contrôlant le rétroéclairage RGB de la carte graphique et en le synchronisant avec celui de vos autres composants compatibles avec Mystic Light.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:600,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/MSI/RTX%204000/800-4090gaming.jpg&quot;,&quot;width&quot;:800}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/MSI/RTX%204000/800-4090gaming.jpg\" width=\"800\" height=\"600\"><figcaption class=\"attachment__caption\"></figcaption></figure></div><div><strong>NVIDIA Ada Lovelace et DLSS3 : toujours plus loin<br></strong><br></div><div>Avec l\'architecture Ada Lovelace, NVIDIA ne se contente pas seulement de proposer un gain de performances conséquent par rapport à la génération précédente, mais également une amélioration des technologies RTX et surtout l\'inauguration du DLSS 3. Optimisé par les coeurs Tensor de quatrième génération et l\'accélérateur de flux optiques des GPU GeForce RTX série 40, le DLSS 3 exploite l\'IA pour générer des images additionnelles de haute qualité sans altérer la qualité ni la réactivité des images.<br><br></div><div><strong>Les outils pour votre créativité<br></strong><br></div><div>Et parce que les RTX série 4000 visent également les porteurs de projets créatifs, leurs performances sont à même de faciliter vos rendus 3D, montages vidéo et conceptions graphique. Grâce aux capacités d\'accélération dans les principales applications de création du marché ainsi qu\'aux pilotes NVIDIA Studio conçus pour garantir un maximum de stabilité, vous profitez d\'un panel d\'outils exclusifs exploitant la puissance de RTX pour un environnement de création productif assisté par l\'IA.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:800,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/Nvidia/800-rtx4000-3.jpg&quot;,&quot;width&quot;:900}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/Nvidia/800-rtx4000-3.jpg\" width=\"900\" height=\"800\"><figcaption class=\"attachment__caption\"></figcaption></figure></div>'),
(15, 2, 4, 2, '2024-01-30 00:00:00', 'Asus Phoenix Radeon RX 550 - 2 Go (PH-550-2G)', 'Radeon RX 550, PCI-Express 3.0, 2 Go GDDR5', '<div>La carte graphique Asus Phoenix Radeon RX 550 a été pensée dans un format compact idéal pour les jeux PC avec une forte scène eSport : Overwtach, Dota 2, CSGo... Les performances qu\'il vous faut pour un budget contenu !</div>', '01HND9N7DVFJGWCANKX5C2WKTX.webp', 89.95, 20, 0, '<div><strong>Nouvelle architecture AMD : Polaris !<br></strong><br></div><div>Nouvelle déclinaison de la gamme des AMD Radeon RX 500, la Asus Phoenix RX 550 2 Go s\'appuie sur l\'architecture AMD Polaris. Utilisant une nouvelle technologie de gravure en 14 nanomètres, la Asus RX 550 affiche son objectif : rendre accessible le jeu PC au plus grand nombre !<br><br></div><div>Affichant un prix défiant toute concurrence, la RX 550 vous permettra de jouer aux derniers titres de eSport en toute fluidité.<br><br></div><div>Immergez vous dans la nouvelle génération de jeux PC en douceur avec un prix contenu et aux performances intéressantes. La Asus Radeon RX 550 Pulse embarque 2 Go de mémoire de type DDR5 elle vous apportera une fluidité et un confort de jeu optimal.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:300,&quot;url&quot;:&quot;https://media.materiel.net/oproducts/AR201802220163_d0.jpg&quot;,&quot;width&quot;:400}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/oproducts/AR201802220163_d0.jpg\" width=\"400\" height=\"300\"><figcaption class=\"attachment__caption\"></figcaption></figure><br><br></div><div><strong>IP5X, 0 dB fan... le savoir faire ASUS !<br></strong><br></div><div>La <strong>Asus Phoenix Radeon RX 550</strong>intègre les dernières innovations ASUS et tout le savoir faire gaming de la marque. Asus produit ses cartes via le technologie industrielle Auto-Extreme qui garantit un processus 100% automatique incluant matériau premium et standard de qualité au top.<br><br></div><div>Cette carte graphique ASUS embarque des ventilateurs IPX5 brevetés. Ils délivreront un flux d\'air maximal et résistant à la poussière pour une durée de vie prolongée au maximum.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:300,&quot;url&quot;:&quot;https://media.materiel.net/oproducts/AR201802220163_d1.jpg&quot;,&quot;width&quot;:400}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/oproducts/AR201802220163_d1.jpg\" width=\"400\" height=\"300\"><figcaption class=\"attachment__caption\"></figcaption></figure></div>'),
(16, 2, 4, 2, '2024-01-30 01:00:00', 'Asus GeForce RTX 4060 Ti DUAL OC', 'GeForce RTX 4060 Ti, PCI-Express 4.0 x8, 8 Go GDDR6, DLSS 3', '<div>La carte graphique Asus GeForce RTX 4060 Ti DUAL OC met à disposition toutes les performances de l\'architecture NVIDIA Ada Lovelace et la puissance combinée du ray-tracing et du DLSS3 pour vous permettre de créer votre configuration PC gamer, qui vous fera vibrer en 1080p dans les meilleures conditions !</div>', '01HNDB4D6JPS6ED7CR7YZC253X.webp', 469.95, 20, 0, '<div><strong>La reine du 1080p<br></strong><br></div><div>L\'objectif premier de cette carte graphique Asus GeForce RTX 4060 Ti DUAL OC, faire de votre configuration PC Gamer la machine idéale pour exceller en 1080p, le tout en profitant du gain de performances et de qualité des technologies DLSS3 et ray-tracing.<br><br></div><div>Ce modèle est prêt à vous épauler avec:<br><br></div><ul><li>Double ventilateurs axiaux pour un refroidissement excellent</li><li>Plaque arrière en aluminium, pour un effet esthétique et dissipateur</li><li>8 Go de mémoire GDDR6 et un total de 4352 coeurs CUDA<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:550,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/ASUS/RTX%204000/800-4060tdual.jpg&quot;,&quot;width&quot;:800}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/ASUS/RTX%204000/800-4060tdual.jpg\" width=\"800\" height=\"550\"><figcaption class=\"attachment__caption\"></figcaption></figure></li></ul><div><strong>Ray-tracing et DLSS3 : toujours plus loin<br></strong><br></div><div>Avec l\'architecture Ada Lovelace, NVIDIA ne se contente pas de proposer un gain de performances conséquent par rapport à la génération précédente, mais également une amélioration des technologies RTX et surtout l\'inauguration du DLSS 3.<br><br></div><div>Optimisé par les coeurs Tensor de 4e génération, le DLSS 3 exploite l\'Intelligence Artificelle pour améliorer les performances graphiques et la résolution de vos jeux grâce à la génération d\'images additionnelles de haute qualité, sans altérer la qualité ni la réactivité.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:460,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/Nvidia/RTX%204000/800-nv-4060ti-2.jpg&quot;,&quot;width&quot;:800}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/Nvidia/RTX%204000/800-nv-4060ti-2.jpg\" width=\"800\" height=\"460\"><figcaption class=\"attachment__caption\"></figcaption></figure><br><br></div><div><strong>La plateforme pour les joueurs et les créateurs<br></strong><br></div><div>Et parce que les RTX série 4000 visent également les porteurs de projets créatifs, leurs performances viennent avec leur lot de fonctionnalités :<br><br></div><ul><li>Meilleure réactivité avec la plateforme NVIDIA Reflex</li><li>Conception pour le streaming en direct grâce à l\'encodeur NVIDIA</li><li>Vidéos et voix optimisées par l\'IA via l\'application NVIDIA Broadcast</li><li>Performances et stabilité au rendez-vous avec les pilotes Game ready et Studio<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:450,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/Nvidia/RTX%204000/800-nv-4060ti.jpg&quot;,&quot;width&quot;:800}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/Nvidia/RTX%204000/800-nv-4060ti.jpg\" width=\"800\" height=\"450\"><figcaption class=\"attachment__caption\"></figcaption></figure></li></ul>'),
(17, 7, 4, 2, '2024-01-30 02:00:00', 'MSI GeForce RTX 4060 GAMING X 8G', 'GeForce RTX 4060, PCI-Express 4.0 8x, 8 Go GDDR6, DLSS 3', '<div>La carte graphique MSI GeForce RTX 4060 GAMING X 8G met à disposition toutes les performances de l\'architecture NVIDIA Ada Lovelace et la puissance combinée du ray-tracing et du DLSS3 pour vous permettre de créer votre configuration PC gamer, qui vous fera vibrer en 1080p dans les meilleures conditions !</div>', '01HNDBJ8SQ15SZ67F1C01204MJ.webp', 409.96, 20, 0, '<div><strong>Pour jouer en Full HD<br></strong><br></div><div>L\'objectif premier de cette carte graphique MSI GeForce RTX 4060 GAMING X 8G, faire de votre configuration PC Gamer la machine idéale pour jouer en 1080p, le tout en profitant du gain de performances et de qualité des technologies DLSS3 et ray-tracing.<br><br></div><div>Ce modèle est prêt à vous épauler avec:<br><br></div><ul><li>Conception thermique TRI Frozr 9 pour une dissipation de la chaleur optimale</li><li>Ventilateurs Torx 5.0, au design pensé pour maintenir un débit d\'air efficace</li><li>Utilitaires MSI Center et Afterburner pour un contrôle précis de vos performances</li><li>8 Go de mémoire GDDR6 et un total de 3072 coeurs CUDA<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:550,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/MSI/RTX%204000/800-4060gx.jpg&quot;,&quot;width&quot;:800}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/MSI/RTX%204000/800-4060gx.jpg\" width=\"800\" height=\"550\"><figcaption class=\"attachment__caption\"></figcaption></figure></li></ul><div><strong>Ray-tracing et DLSS3 : toujours plus loin<br></strong><br></div><div>Avec l\'architecture Ada Lovelace, NVIDIA ne se contente pas de proposer un gain de performances conséquent par rapport à la génération précédente, mais également une amélioration des technologies RTX et surtout l\'inauguration du DLSS 3.<br><br></div><div>Optimisé par les coeurs Tensor de 4e génération, le DLSS 3 exploite l\'Intelligence Artificelle pour améliorer les performances graphiques et la résolution de vos jeux grâce à la génération d\'images additionnelles de haute qualité, sans altérer la qualité ni la réactivité.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:460,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/Nvidia/RTX%204000/800-nv-4060ti-2.jpg&quot;,&quot;width&quot;:800}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/Nvidia/RTX%204000/800-nv-4060ti-2.jpg\" width=\"800\" height=\"460\"><figcaption class=\"attachment__caption\"></figcaption></figure><br><br></div><div><strong>La plateforme pour les joueurs et les créateurs<br></strong><br></div><div>Et parce que les RTX série 4000 visent également les porteurs de projets créatifs, leurs performances viennent avec leur lot de fonctionnalités :<br><br></div><ul><li>Meilleure réactivité avec la plateforme NVIDIA Reflex</li><li>Conception pour le streaming en direct grâce à l\'encodeur NVIDIA</li><li>Vidéos et voix optimisées par l\'IA via l\'application NVIDIA Broadcast</li><li>Performances et stabilité au rendez-vous avec les pilotes Game ready et Studio<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:450,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/Nvidia/RTX%204000/800-nv-4060ti.jpg&quot;,&quot;width&quot;:800}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/Nvidia/RTX%204000/800-nv-4060ti.jpg\" width=\"800\" height=\"450\"><figcaption class=\"attachment__caption\"></figcaption></figure></li></ul>'),
(18, 8, 6, 2, '2024-01-30 03:00:00', 'AMD Ryzen 7 5700G', '8 coeurs, 3.80 GHz, 16 Mo, AMD Ryzen, 65 Watts, version boite avec ventilateur', '<div>Le processeur AMD Ryzen 7 5700G offre une base solide pour monter une configuration multimedia et même gaming orientée jeux eSport ! Radeon Vega 8 pour les performances graphiques, architecture Zen 3 gravée en 7 nm pour les applications : AMD propose à nouveau un processeur à l\'excellent rapport prestations-prix !</div>', '01HNDFFHFXS9KPQEFQC6N2BY4J.webp', 239.95, 20, 0, '<div><strong>AMD Ryzen : Architecture Zen 3<br></strong><br></div><div>Profitant d\'une finesse de gravure 7 nanomètres, et de l\'architecture AMD Zen 3, le processeur Ryzen 7 5700G délivre des performances de pointe grâce à ses <strong>8 coeurs</strong> ultra-véloces et ses <strong>16 threads</strong>, ses <strong>16 Mo</strong> de cache et sa fréquence native <strong>3,80 Ghz</strong> (allant jusqu\'à <strong>4,6 GHz</strong> en mode Turbo).<br><br></div><div>Exploitez la puissance hors du commun des processeurs AMD sans sacrifier l\'efficacité énergétique. Véritable prouesse, le processeur AMD <strong>Ryzen 7 5700G</strong> offre des fréquences de fonctionnement élevées pour une consommation électrique mesurée avec une enveloppe thermique (TDP) de seulement <strong>65W</strong>.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:350,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/processeurs/amd/AR201801170059_d2.jpg&quot;,&quot;width&quot;:400}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/composants%20pc/processeurs/amd/AR201801170059_d2.jpg\" width=\"400\" height=\"350\"><figcaption class=\"attachment__caption\"></figcaption></figure></div><div><strong>AMD Radeon Vega Graphics 8<br></strong><br></div><div>Multitache, utilisation intensive, jeux extrême, édition avancée de contenu numérique, Réalité Virtuelle (VR), AMD propose une solution ultra-polyvalente et immédiatement utilisable avec une carte mère socket AM4 (<strong>A520, B550, X570</strong>). Le processeur <strong>AMD Ryzen 7 5700G</strong> possède 8 coeurs Radeon Vega pour une fréquence <strong>2000 MHz</strong>, le tout fusionné en une puce graphique unique permettant d\'offrir toutes les performances dont vous avez besoin pour des tâches exigeantes et de sérieuses possibilités de jeu, et ce, sans faire autant de compromis que cela.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:400,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/processeurs/amd/vegagraphics.jpg&quot;,&quot;width&quot;:600}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/composants%20pc/processeurs/amd/vegagraphics.jpg\" width=\"600\" height=\"400\"><figcaption class=\"attachment__caption\"></figcaption></figure></div>'),
(19, 4, 6, 2, '2024-01-30 04:00:00', 'Intel Core i7 12700F', '12 coeurs, 20 threads, 2.1 GHz, 25 Mo, Alder Lake, 65/180 Watts, BX8071512700F', '<div>Nouvel arrivant de la 12ème génération de processeurs Intel Alder Lake, le Intel Core i7-12700F. Grâce à son architecture hybride alliant P-Core et E-Core, et épaulé par le support de la norme PCIe 5.0 et de la mémoire DDR5, il se destine aux configurations PC cherchant des performances optimales. Que ce soit pour du multitâche extrême, de la création de contenu multimedia ou du streaming, il est prêt à répondre à tous vos besoins.</div>', '01HNDFQTQRQQ67MBPYV2H131Z2.webp', 349.96, 20, 0, '<div><strong>Intel 12ème génération : DDR5 et PCIe 5.0<br></strong><br></div><div>Avec Alder Lake, la 12ème génération de processeurs Intel, la marque propose une approche hybride de l\'architecture x86. Jusqu\'alors, Intel incorporait un certain nombre de coeurs dans ses processeurs, chacun identique. Avec Alder Lake, deux types de coeurs sont associés : les Performance-Cores, centrés sur... la performance et utilisés pour les tâches gourmandes en ressources et les Efficient-Cores, plus économes en énergie et pensés pour gérer les tâches de fond. Grâce à cette répartition des tâches, principalement géré via l\'Intel Thread Director, le gain de performances annoncée serait de 19% par rapport à la 11ème génération.<br><br></div><div>Côté technologies, Alder Lake supporte la norme PCIe 5.0 pour la liaison avec la carte graphique (les SSD étant sur des lignes PCIe 4.0) ainsi que la mémoire DDR5, (fréquence de 4800 MHz en natif). La double compatibilité avec la DDR4 est toujours possible (DDR4-3200).<br><br></div><div>Les processeurs Intel Core de 12ème génération sont compatibles uniquement avec les cartes mères des chipsets séries 600. Destiné aux configurations orientées jeu/performances, ce modèle ne dispose pas de contrôleur graphique intégré et nécessite l\'installation d\'une carte graphique dédiée.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:600,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/processeurs/12th%20gen/600-0.jpg&quot;,&quot;width&quot;:600}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/composants%20pc/processeurs/12th%20gen/600-0.jpg\" width=\"600\" height=\"600\"><figcaption class=\"attachment__caption\"></figcaption></figure></div><div><strong>Performances gaming et multitâche<br></strong><br></div><div>Positionné en haut de gamme de l\'architecture <strong>Alder Lake</strong> sur <strong>socket 1700</strong>, le processeur <strong>Core i7 12700</strong> affiche un compteur de 12 coeurs (dont 8 P-cores et 4 E-core) pour 20 Threads ainsi qu\'une fréquence jusqu\'à 4,9 GHz.<br><br></div><div>Pensé pour l\'efficacité, ce processeur se démarque en jeu ou sur de l\'applicatif et du multi-tâche en vous offrant des performances à même de répondre à vos besoins.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:350,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/processeurs/12th%20gen/600-i7nonK.jpg&quot;,&quot;width&quot;:600}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/composants%20pc/processeurs/12th%20gen/600-i7nonK.jpg\" width=\"600\" height=\"350\"><figcaption class=\"attachment__caption\"></figcaption></figure></div>'),
(20, 5, 7, 2, '2024-01-30 05:00:00', 'be quiet! Pure Base 500DX - Noir', 'Moyenne tour, ATX / Micro ATX / Mini ITX, Noir, ARGB', '<div>Be Quiet décline une nouvelle série de boitiers, le Pure Base 500DX. C\'est un boitier pour PC qui s\'adresse aux gamers, avec un refroidissement performant, un beau panneau latéral en verre trempé et des LEDs RGB en façade pour personnaliser votre PC. Sobre, élégant et conçu avec des matériaux de qualité, il est idéal pour accueillir votre configuration gamer en ATX, Micro ATX et Mini ITX.</div>', '01HNDG0RMVW4GPKVRYTHESCKBD.webp', 119.95, 20, 0, '<div><strong>Le boîtier parfait pour votre PC gamer<br></strong><br></div><div>Le <strong>Be Quiet Pure Base 500DX</strong> est un boîtier PC au format standard moyen tour capable d\'accueillir des cartes mères ATX / Micro ATX ou Mini ITX. Il peut prendre en charge une carte graphique de 369mm, un ventirad de 190mm et une alimentation ATX/EPS de 258mm. Grâce à ces dimensions généreuses : Concevez votre configuration gamer autour de ce beau boîtier qui possède un panneau latéral en verre trempé pour admirer vos composants.<br><br></div><div>Le refroidissement est très important pour un pc de jeu, c\'est pourquoi <strong>Be Quiet</strong> a équipé le <strong>Pure Base 500DX</strong> de 3 ventilateurs PURE WINGS 2 pré-installés. Ce sont des ventilateurs de 140 mm, fixés en façade, à l\'arrière et sur le dessus du boîtier. Le flux d\'air est bien optimisé pour apporter de l\'air frais aux composants et expulser l\'air chaud du PC afin de profiter pleinement de tout son potentiel.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:600,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/boital/bequiet!/purte%20base%20500dx/texte1.jpg&quot;,&quot;width&quot;:600}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/composants%20pc/boital/bequiet!/purte%20base%20500dx/texte1.jpg\" width=\"600\" height=\"600\"><figcaption class=\"attachment__caption\"></figcaption></figure></div><div><strong>Des fonctionnalités bien pensées !<br></strong><br></div><div>Chaque gamer a son style, le <strong>Pure Base 500DX</strong> est doté de LEDs ARGB en façade dont les effets sont personnalisables. Vous pouvez aussi synchroniser l\'éclairage avec votre carte mère compatible ou votre contrôleur ARGB pour que tous vos composants s\'illuminent ensemble. En ce qui concerne le stockage, ce boîtier Be Quiet possède 2 baies pour les disques 3.5\" HDD et 5 baies au format 2.5\" pour vos disques SSD.<br>La connectique en façade est impeccable, avec un port USB 3.1 Type C Gen. 2 permettant l\'utilisation du matériel le plus récent, un connecteur USB 3.0. Il possède également un interrupteur pour contrôler les LED intérieures et extérieures.<br><br></div><div>Si vous souhaitez installés des ventilateurs supplémentaires, le <strong>Pure Base 500DX</strong> peut accueillir jusqu\'à 3 ventilateurs en façade pour perfectionner votre installation. Il peut également prendre en charge un kit de watercooling avec un radiateur compris entre 120 et 240 mm : idéal pour les processeurs haut de gamme.<br>Le système de passe-câbles et rangement de câbles permettent d\'avoir une configuration propre sans fils qui dépassent.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:600,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/boital/bequiet!/purte%20base%20500dx/texte2.jpg&quot;,&quot;width&quot;:600}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/composants%20pc/boital/bequiet!/purte%20base%20500dx/texte2.jpg\" width=\"600\" height=\"600\"><figcaption class=\"attachment__caption\"></figcaption></figure></div>'),
(21, 9, 7, 2, '2024-01-30 06:00:00', 'Phanteks Enthoo Luxe 2 - Noir', 'Grand Tour, ATX / E-ATX / Micro ATX / Mini ITX, sans alim, Noir, ARGB', '<div>Voici un nouveau boitier Phanteks, le magnifique Enthoo Luxe 2, un écrin au format grande tour capable de prendre en charge ATX / E-ATX / Micro ATX / Mini ITX ou SSI-EEB. Le boitier a été désigné pour accueillir des configurations puissantes, le tout avec une grande flexibilité. Il réserve une grosse capacité de stockage avec jusqu\'à 12 disques durs de 3.5\" et 11 disques durs de 2.5\". Pour le refroidissement Phanteks offre 15 emplacements de ventilateurs, ce qui est idéal pour un kit watercooling. C\'est un boitier haut de gamme qui saura combler les joueurs les plus exigeants.</div>', '01HNDGDDMJN7G9RRMCZQ3GQC2M.webp', 214.94, 20, 0, '<div><strong>Un boitier de très haute qualité<br></strong><br></div><div><strong>Une personnalisation incroyable<br></strong><br></div><div>Le boitier <strong>Phanteks Enthoo Luxe 2</strong> propose une haute flexibilité dans le montage et le choix de vos composants. En effet, le boitier est au format grand tour, ce qui permet d\'envisager une configuration avec des cartes mères <strong>ATX / E-ATX / Micro ATX / Mini ITX ou SSI-EEB</strong>. Pour votre carte graphique, la limite se situe à 503 mm de long, de quoi faire rentrer l\'intégralité des productions sur le marché à l\'heure actuelle sans aucun problème et une hauteur de ventilateur jusqu\'à 195 mm de haut.<br><br></div><div>On a rarement vu un boitier avec autant de baies de stockage que sur ce <strong>Enthoo Luxe 2</strong>. Phanteks vous réserve une grosse capacité de stockage avec jusqu\'à 12 disques durs de 3.5\" et 11 disques durs de 2.5\". Cela vous laisse énormément de possbilités d\'installater des tonnes de données pour vos jeux et autres applications.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:600,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/boital/phanteks/enthoo%20luxe%202%20-%20noir/texte1.jpg&quot;,&quot;width&quot;:600}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/composants%20pc/boital/phanteks/enthoo%20luxe%202%20-%20noir/texte1.jpg\" width=\"600\" height=\"600\"><figcaption class=\"attachment__caption\"></figcaption></figure></div><div><strong>Une grand potentiel de refroidissement<br></strong><br></div><div>Conçu principalement pour les joueurs, le <strong>Phanteks Enthoo Luxe 2</strong> là encore tire le meilleur de son très grand gabarit avec un total de 15 emplacements de 120 mm pour installer vos ventilateurs. Grâce à cette multitude d\'emplacement, vous pouvez fixer un kit de watercooling qui possède un radiateur de 120 à 480 mm pour refroidir très efficacement votre processeur Intel ou AMD. Optimisez votre flux d\'air en mettant un maximum de ventilateurs. L\'objectif est vraiment que vos composants restent bien au frais surtout si vous avez une configuration puissante pour en conserver toutes les performances.<br><br></div><div>Le refroidissement est un élément capital pour un PC gaming, et <strong>Phanteks </strong>met tout en oeuvre pour satisfaire les utilisateurs les plus exigeant avec la possibilité d\'installer des ventilateurs 140 mm avec 8 emplacements au total pour varier la disposition de vos ventilateurs. La poussière n\'est pas la bienvenue dans votre installation, et par conséquent 3 filtres à poussière ont été ajoutés à l\'ensemble.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:600,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/boital/phanteks/enthoo%20luxe%202%20-%20noir/texte2.jpg&quot;,&quot;width&quot;:600}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/composants%20pc/boital/phanteks/enthoo%20luxe%202%20-%20noir/texte2.jpg\" width=\"600\" height=\"600\"><figcaption class=\"attachment__caption\"></figcaption></figure></div><div><strong>Un design moderne et élégant<br></strong><br></div><div>Le <strong>Phanteks Enthoo Luxe 2</strong> se situe sur le segment des boitiers PC haut de gamme. Il a été conçu avec des matériaux de grande qualité, de l\'acier, de l\'aluminium et du verre trempé bien épais pour le panneau latéral.<br><br></div><div>Pour personnaliser votre setup le boitier est doté d\'un discret mais joli liseré RGB sur la façade et au niveau du cache de l\'alimentation. De plus, il est même <strong>ARGB </strong>: c\'est-à-dire que vous pouvez synchroniser les effets lumineux avec les systèmes de gestion Asus, MSI, Razer et Aorus. De quoi créer un ensemble unique et totalement original à vôtre image.<br><br></div><div>Au niveau de la connectique, c\'est 1 port <strong>USB 3.1 Gen 2</strong>, <strong>3 ports USB 3.0</strong>, les entrés et orties audio classiques, ainsi que des boutons pour contrôler le rétroéclairage RGB.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:600,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/boital/phanteks/enthoo%20luxe%202%20-%20noir/texte3.jpg&quot;,&quot;width&quot;:600}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/composants%20pc/boital/phanteks/enthoo%20luxe%202%20-%20noir/texte3.jpg\" width=\"600\" height=\"600\"><figcaption class=\"attachment__caption\"></figcaption></figure></div>');
INSERT INTO `produit` (`id`, `marque_id`, `categorie_id`, `categorie_principale_id`, `date_lancement`, `designation`, `resume`, `descriptif`, `photo`, `prix`, `stock`, `archive`, `descriptif_detaille`) VALUES
(22, 5, 9, 2, '2024-01-31 00:00:00', 'be quiet! Shadow Rock 3', 'Simple tour, Cuivre et aluminium, 1150 / 1151 / 1155 / 2011-v3 / 2066/1700 et AM4, AM5', '<div>Be Quiet confirme toute son expérience sur le marché de l\'informatique avec ce ventirad Shadow Rock 3. Il offre une efficacité de refroidissement impressionnante, tout en restant silencieux pour le confort des utilisateurs. Il permet d\'optimiser les performances de votre processeur Intel ou AMD en s\'installant très rapidement lors du montage de votre machine.</div>', '01HNFAQY44Q1ZAJA76JSWJZJGG.webp', 58.95, 20, 0, '<div><strong>Une efficacité remarquable tout en silence !<br></strong><br></div><div><strong>Votre processeur bien refroidi<br></strong><br></div><div>Pour les utilisateurs qui souhaitent un refroidissement par aircooling performant, le <strong>Shadow Rock 3</strong> est la solution toute indiquée. Le Be Quiet Shadow Rock 3 affiche une TDP max de <strong>190W </strong>ce qui est impressionant ! De plus, il convient à la grande majoritée des processeur AMD ou Intel.<br><br></div><div>La conception des cinq caloducs de 6 mm haute performance avec technologie HDT permet une excellente conduction de la chaleur pour soulager de manière optimale le processeur de votre PC. Il reste aussi treès simple à installer lors du montage, car Be Quiet fourni un tournevis adapté et un kit de montage : parfait lorsque c\'est pour votre première configuration.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:600,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/refroidissmement/refroidissement%20processeur/be%20quiet!/shadow%20rock%203/texte1.jpg&quot;,&quot;width&quot;:600}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/composants%20pc/refroidissmement/refroidissement%20processeur/be%20quiet!/shadow%20rock%203/texte1.jpg\" width=\"600\" height=\"600\"><figcaption class=\"attachment__caption\"></figcaption></figure></div><div><strong>Le silence au coeur de la performance<br></strong><br></div><div>Refroidir efficacement en silence, c\'es tout à fait possible : le <strong>Shadow Rock </strong>3 est équipé d\'un ventilateur <strong>Shadow Wings 2 PWM</strong> de 120 mm. Cela permet, même à plein régime au Shadow Rock 3 de ne pas dépasser les 24.4 dB à 1600 tour/minute. Il se fait discret mais reste très performant pour le plus grand bonheur de votre CPU et de vos oreilles !<br><br></div><div>Ce ventirad Be Quiet peut être installé sur toutes les cartes mères existantes tout en économisant de la place pour laisser les emplacements de mémoire disponibles.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:600,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/refroidissmement/refroidissement%20processeur/be%20quiet!/shadow%20rock%203/texte2.jpg&quot;,&quot;width&quot;:600}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/composants%20pc/refroidissmement/refroidissement%20processeur/be%20quiet!/shadow%20rock%203/texte2.jpg\" width=\"600\" height=\"600\"><figcaption class=\"attachment__caption\"></figcaption></figure></div>'),
(23, 10, 9, 2, '2024-01-31 10:14:52', 'Noctua NH-U12S SE-AM4', 'Simple tour, Aluminium, AM4', '<div>Des dizaines de milliers de passionnés du monde entier ont été séduits par la qualité et la performance du NH-U12S. Recommandé par plus de 200 magazines et sites spécialisés internationaux, ce dernier est devenu un des leaders incontournables sur le marché des ventirads 120mm. Assorti d\'un tube de la célèbre pâte thermique Noctua NT-H1 ainsi que d\'une fixation exclusivement dédiée au socket AM4, le NH-U12S SE-AM4 est la solution ultime pour ceux qui sont en quête d\'un refroidissement silencieux haut de gamme pour leur processeur AMD Ryzen.</div>', '01HNFG7AV2D20NZ0S9S5Y0VKEQ.webp', 98.95, 20, 0, '<div><strong>Une dimension raisonnable pour une meilleure compatibilité<br></strong><br></div><div><strong>Une version spécialement dédiée à l\'AM4<br></strong><br></div><div>Le NH-U12S SE-AM4 permet à la nouvelle plateforme AMD AM4 d\'accéder aux performances acoustiques et de refroidissement qui ont fait la renommée du NH-U12S. Grâce à un dosage parfait combinant efficacité, silence et compatibilité, le NH-U12S SE-AM4 est le compagnon idéal des configurations AM4 haut de gamme.<br><br></div><div><strong>Un format tour de 120mm idéal pour une excellente compatibilité globale<br></strong><br></div><div>Avec une hauteur de 158mm, le NH-U12S est suffisamment court pour pouvoir s\'intégrer dans la plupart des boîtiers actuels, moyen ou haut de gamme, de type « tour ». Pour les cartes mères ATX et Micro-ATX, la largeur de 125mm (ventilateurs et clips de fixation inclus) permet au dissipateur de rester éloigné du dernier slot PCIe, offrant ainsi une meilleure compatibilité avec les configurations SLI et CrossFire.<br><br></div><div><strong>Ventilateur NF-F12 120mm Focused Flow<br></strong><br></div><div>Plébiscité par plus de 150 articles spécialisés et par des milliers d\'utilisateurs dans le monde, le NF-F12 Focused Flow™ est un ventilateur de haut vol célèbre pour sa pression statique étonnante, sa performance de refroidissement et son niveau sonore extrêmement bas.<br>Le ventilateur 120mm NF-F12, livré avec le NH-U12S, peut bénéficier d\'un pilotage automatique de la vitesse PWM via la carte mère. De plus, et pour réduire encore les émissions sonores, la vitesse maximale peut être plafonnée à 1200rpm (au lieu de 1500rpm) grâce à l\'utilisation de l\'adaptateur faible bruit (L.N.A.).<br>Pour ceux qui souhaitent améliorer encore le niveau de performance, l\'ajout d\'un second ventilateur permettra de créer une configuration en « push/pull » (aspiration/extraction). A cette fin, le NH-U12S est livré avec des silentblocs anti-vibration sur-mesure qui permettent de décaler le ventilateur arrière de 5mm afin d\'améliorer le confort acoustique en mode dual (double ventilateur).<br><br></div><div><strong>Un système de fixation SecuFirm2™ pour l\'AM4<br></strong><br></div><div>Les systèmes de fixation Noctua SecuFirm2™ sont devenus les références ultimes au niveau de la qualité, de la fiabilité et de la simplicité de montage. Afin de garantir une excellente compatibilité mais aussi un flux optimal tenant compte des contraintes du boîtier, le système SecuFirm2™ pour AM4 permet d\'installer le ventirad de manière traditionnelle ou bien en pivot à 90°.</div>'),
(24, 7, 14, 2, '2024-01-31 10:22:47', 'MSI MAG CORELIQUID C280', '1 x 280, Kit Watercooling AIO, ARGB', '<div>MSI décide d\'étendre son offre de composants PC en créant pour vous sa gamme de kit de watercooling AIO. Le MSI MAG CORELIQUID C280est un kit All-In-One comprenant un radiateur de 280 mm avec deux ventilateurs ARGB pour refroidir efficacement votre processeur.</div>', '01HNFGNTJKRC2XFZTJRKMC8TMJ.webp', 83.95, 20, 0, '<div><strong>Un kit de watercooling MSI ARGB performant pour votre PC<br></strong><br></div><div><strong>Pas de compromis : performance et personnalisation au maximum !<br></strong><br></div><div>La marque au Dragon, experte en composants <strong>MSI </strong>élargit son périmètre produit en concevant pour vous une gamme de kit de watercooling pour refroidir efficacement votre processeur Intel ou AMD. En effet, le MSI MAG CORELIQUID C280 est compatible avec les sockets AMD AM2(+), AM3(+), AM4, FM1, FM2(+) et les sockets Intel 1150, 1151, 1155, 1156, 1200, 1366, 2011, 2011-v3, 2066 et surtout 1700.<br><br></div><div>Ce kit All-In-One MSI est composé d\'<strong>un radiateur de 280 mm</strong> avec deux ventilteurs PWM ARGB de 140 mm, de tuyaux fléxibles robustes et afin de la pompe ARGB estampillée du logo MSI. L\'autre particularité de cette pompe c\'est qu\'elle est équipée d\'un moteur triphasé durable promettant une belle longévité et la faible production de vibration en fonctionnement. De plus, la pompe intégrée au radiateuri permet une dissipation de la chaleur rapide dans le radiateur. Le&nbsp; moteur est quand à lui triphasé pour des vibrations réduites.</div><div><br></div><div>En connectant bien votre MSI MAG CORELIQUID C280 sur les ports <strong>RGB </strong>de votre carte mère, vous pourrez personnaliser à volonté les effets lumineux de votre kit de watercooling. De plus, Connectez-vous et synchronisez le MAG CORELIQUID C280 avec un boîtier MSI pourvu de la <strong>technologie DIY 2.0</strong>.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:600,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/refroidissmement/refroidissement%20processeur/msi/coreliquid%20c/texte.jpg&quot;,&quot;width&quot;:600}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/composants%20pc/refroidissmement/refroidissement%20processeur/msi/coreliquid%20c/texte.jpg\" width=\"600\" height=\"600\"><figcaption class=\"attachment__caption\"></figcaption></figure></div>'),
(25, 3, 14, 2, '2024-01-31 12:31:13', 'Corsair ICUE H55 RGB - Noir', '1 x 120, Kit Watercooling AIO, ARGB', '<div>Plongez dans un monde de refroidissement silencieux et de style éblouissant avec le Corsair iCUE H55 RGB. Conçu pour les utilisateurs exigeants qui ne veulent faire aucun compromis entre performance et esthétique, ce système de refroidissement liquide apporte une solution élégante à la chaleur de votre processeur.</div>', '01HNFR1033BGM9T7N0R29MDR8P.webp', 99.95, 20, 0, '<div><strong>Corsair iCUE H55 RGB : Performance Frigorifique Élégante<br></strong><br></div><div><strong>Refroidissement Optimal et éclairage synchronisé<br></strong><br></div><div>Avec son radiateur compact et son bloc-pompe à faible encombrement, le Corsair iCUE H55 RGB offre une dissipation thermique optimale sans sacrifier l\'espace dans votre boîtier. Les pales du ventilateur garantissent un flux d\'air continu, permettant à votre processeur de rester au frais même lors des tâches les plus gourmandes en ressources. Le ventilateur inclus, optimisé pour un fonctionnement silencieux, maintient le bruit au minimum tout en maximisant la performance de refroidissement. Jouez sans interruption, en toute tranquillité. Profitez d\'une installation sans tracas avec le Corsair iCUE H55 RGB. Le kit complet fourni facilite le processus d\'installation, même pour les utilisateurs novices. Transformez votre système en un clin d\'œil et profitez de performances optimales.<br><br></div><div>Donnez vie à votre boîtier avec l\'éclairage RGB personnalisable du Corsair iCUE H55. Intégré au logo Corsair sur le bloc-pompe, l\'éclairage RGB peut être synchronisé avec d\'autres périphériques Corsair compatibles iCUE, créant une ambiance visuelle harmonieuse dans votre configuration.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:496,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/Composants%20PC/Refroidissmement/Refroidissement%20Processeur/Corsair/H55/texte.JPG&quot;,&quot;width&quot;:549}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/Composants%20PC/Refroidissmement/Refroidissement%20Processeur/Corsair/H55/texte.JPG\" width=\"549\" height=\"496\"><figcaption class=\"attachment__caption\"></figcaption></figure></div>'),
(26, 3, 10, 2, '2024-01-31 12:46:07', 'Corsair Dominator Platinum RGB - 4 x 8 Go (32 Go) - DDR4 3600 MHz - CL18', 'Kit RAM DDR4, 32 Go, 3600 MHz, PC28800, CL18-22-22-42, 1,35 Volts, CMT32GX4M4D3600C18', '<div>Corsair s\'est taillé une excellente réputation parmi les constructeurs de barrettes mémoire. La Corsair Dominator Platinum RGB représente l\'aboutissement de 25 années passées à concevoir des barrettes mémoire à la pointe de l\'exigence. Marque aujourd\'hui renommée dans l\'univers du gaming, Corsair a développée des modules DDR4 Dominator Platinum RGB à LED Capellix d\'une fiabilité incroyable, avec une garantie limitée à vie, et qui offrent des performances de haute volée avec un système de refroidissement breveté Dual-Path DHX exceptionnel d\'efficacité. Le design de cette gamme Dominator Platinum RGB est d\'une finition également exceptionnelle. Pour tirer le meilleur parti de votre ordinateur gamer, rejoignez les utilisateurs de la marque Corsair.</div>', '01HNFRW92HNB9XTHEPXN2VDWKP.webp', 248.95, 20, 0, '<div><strong>La Dominator Platinum RGB \"Connected Edition\" ou l\'iCUE maîtrisé grâce aux Capellix.<br></strong><br></div><div><strong>Mémoire DDR4 : Hautes performances, Gaming, Overclocking et Dissipation<br></strong><br></div><div>La mémoire DDR4 <strong>Dominator Platinum RGB Black</strong> de <strong>Corsair</strong> est conçue pour délivrer l\'une des meilleures performances au monde, elle est idéale pour les PC de dernière génération telle que l\'architecture <strong>Intel Coffee Lake S</strong> ou <strong>AMD Ryzen</strong>. Optez pour une solution ultra-performante, les modules de mémoire DDR4 <strong>Dominator Platinum RGB Black</strong> repoussent les limites les plus extrêmes grâce à un overclocking des plus efficaces avec votre processeur et votre carte-mère.<br><br></div><div>Quand refroidissement et style sont réunis. Le design des dissipateurs des modules <strong>Dominator Platinum RGB Black</strong> allient l\'efficacité de l\'aluminium - excellent dissipateur thermique - à celui d\'un style unique réhaussé par des <strong>Leds Capellix</strong> haute luminosité. Le système de refroidissement breveté Dual-Path DHX refroidit non seulement les puce mémoire mais également l\'ensemble du circuit imprimé. Arborez fièrement un look moderne, <strong>brillant</strong> et personnalisable grâce à une construction innovante double finition (aluminium brossé / patiné) des radiateurs. Les configurations PC gamers, extrêmes et overclokées combineront des performances incroyables et un style unique. Réalisez la machine de vos rêves, vivez avec passion votre goût pour la belle mécanique ! Jouez avec les capacités de votre éclairage RGB grâce à une finition unique grillagée.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:663,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/m%c3%a9moire/corsair/dominator_platinum_rgb_black/visu001dprgbbktris.jpg&quot;,&quot;width&quot;:720}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/composants%20pc/m%c3%a9moire/corsair/dominator_platinum_rgb_black/visu001dprgbbktris.jpg\" width=\"720\" height=\"663\"><figcaption class=\"attachment__caption\"></figcaption></figure></div><div><strong>Corsair Platinum RGB : compatibilité et haute fiabilité<br></strong><br></div><div>Plus économe en énergie. La mémoire Corsair DDR4 permet à votre ordinateur un fonctionnement plus rapide en plus d\'une consommation électrique 20% inférieure à celle de la DDR3 à fréquence équivalente). Compatible <strong>Intel XMP 2.0</strong>. Les modules DDR4 sont compatibles avec les derniers profils Intel XMP 2.0 (<strong>Extreme Memory Profile</strong>). Cette fonction vous permet de surcadencer la mémoire afin d\'obtenir des performances très supérieures à la normale. Vous souhaitez overclocker votre machine (fréquences, timings et tensions) et exploiter au maximum les performances de votre PC, la mémoire <strong>Corsair Dominator Platinum RGB Black</strong> est faite pour vous. Le PCB est le premier garant de cette fiabilité avec un circuit imprimé au design \"maison\" personnalisé et unique à cette gamme de barrettes mémoire basé sur 10 couches de substrats haute densité.<br><br></div><div><strong>Garantie à vie</strong>. Toutes les modules de mémoire Corsair bénéficient d\'une <strong>garantie limitée à vie</strong> et respectent une validation rigoureuse à la fabrication. Profitez en toute sérénité de votre matériel, Corsair met à votre disposition un savoir-faire reconnu à travers une fiabilité incroyable associée à une garantie à toute épreuve.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:1280,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/m%c3%a9moire/corsair/dominator_platinum_rgb_black/visu002dprgbbk.jpg&quot;,&quot;width&quot;:1920}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/composants%20pc/m%c3%a9moire/corsair/dominator_platinum_rgb_black/visu002dprgbbk.jpg\" width=\"1920\" height=\"1280\"><figcaption class=\"attachment__caption\"></figcaption></figure></div><div><strong>Offrez un style unique et hautement lumineux à votre PC<br></strong><br></div><div>Appréciez à chaque instant le style dynamique des barrettes de mémoire <strong>Corsair Dominator Platinum RGB Black</strong>. Elles intègrent un ensemble de <strong>12 Leds RGB Capellix </strong>conçus pour une plus grande puissance d\'éclairage, une durabilité supérieure, une consommation d\'énergie inférieure et une configuration individuelle ultra personnalisables !<br><br></div><div>Par défaut réglées sur le mode \"empilement\", les <strong>Corsair Dominator Platinum RGB Black </strong>intègrent <strong>8 modes</strong> d\'éclairage différents. La suite logicielle <strong>iCUE</strong> - <strong>téléchargeable gratuitement</strong> - vous permettra une customisation à l\'infini des effets et des couleurs de vos barrettes. Aucune alimentation supplémentaire ne sera nécessaire. A vous l\'écosystème <strong>iCUE Corsair.</strong><figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:1280,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/m%c3%a9moire/corsair/dominator_platinum_rgb_black/visu003dprgbbk.jpg&quot;,&quot;width&quot;:1920}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/composants%20pc/m%c3%a9moire/corsair/dominator_platinum_rgb_black/visu003dprgbbk.jpg\" width=\"1920\" height=\"1280\"><figcaption class=\"attachment__caption\"></figcaption></figure></div><div>Principales caractéristiques :<br><br></div><ul><li>Des vitesses allant jusqu\'à <strong>4800 MHz</strong></li><li>Fréquences d\'utilisations plus étendues que la DDR3</li><li>Un rapport fréquence / timing de haute volée</li><li>Optimisé pour les profils <strong>Intel XMP 2.0</strong>, réalisez un overclocking facilement</li><li>Compatible plateforme <strong>Intel</strong> et <strong>AMD</strong> de dernière génération</li><li>Pilotez les couleurs et les effets grâce au logiciel de gestion iCUE</li><li>Leds Capellix individuelles plus brillantes, plus durables et moins énergivores</li><li>Plus de réactivité et de bande passante par rapport à la DDR3</li><li>Dissipateurs thermiques uniques, construction aluminium haut de gamme avec <strong>refroidissement du PCB</strong></li><li>Garantie limitée à vie<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:158,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/m%c3%a9moire/corsair/dominator_platinum_rgb_black/visu004dprgbbk.jpg&quot;,&quot;width&quot;:1920}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/composants%20pc/m%c3%a9moire/corsair/dominator_platinum_rgb_black/visu004dprgbbk.jpg\" width=\"1920\" height=\"158\"><figcaption class=\"attachment__caption\"></figcaption></figure></li></ul>'),
(27, 3, 10, 2, '2024-01-31 13:39:28', 'Corsair Vengeance Black - 2 x 16 Go (32 Go) - DDR5 4800 MHz - CL40', 'Kit RAM DDR5, 32 Go, 4800 MHz, PC38400, CL40-40-40-77, 1,1 Volts, CMK32GX5M2A4800C40', '<div>Corsair ne s\'est pas fait attendre avant de sortir ses premiers kits de mémoire DDR5 ! Découvrez la série Vengeance qui vous offre plus de bande passante que la DDR4 ainsi que des performances excellentes sur le long terme, grâce notamment à une conception qui favorise le refroidissement du PCB ainsi que la régulation d\'alimentation automatique généralisée.</div>', '01HNFVXY85DD184EVF16BZETN8.webp', 141.95, 20, 0, '<div><strong>La DDR5 Low Profile optimisée Intel 600 Series<br></strong><br></div><div><strong>Un module DDR5 très avancé<br></strong><br></div><div>La <strong>mémoire DDR5</strong> <strong>Vengeance </strong>de Corsair est conçue pour fonctionner avec les cartes mères basées sur l\'architecture processeur <strong>Intel Alder Lake</strong> en LGA1700 afin de tirer le maximum de ses possibilités. Les cartes mères à base de contrôleur principal Intel 600 Series sont basées sur cette architecture.<br><br></div><div>Ce modèle profite d\'un <strong>design low-profile</strong> et intègre 2 barrettes de 16 Go, pour un total de 32<strong> Go</strong> de mémoire à <strong>4800 MHz</strong>. Grâce à leur <strong>dissipateur thermique intégré</strong> en aluminium et un PCB à <strong>8 couches</strong>, les <strong>Vengeance </strong>assurent un refroidissement excellent du PCB (idéal pour les environnements exigeants et des performances durables !).<br><br></div><div>Enfin, la mémoire <strong>DDR5</strong> offre plus de bande passante par rapport à la DDR4 ainsi que des fréquences d\'utilisations plus étendues à terme.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:600,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/m%c3%a9moire/corsair/vengeance%20black%20ddr5/visu001.jpg&quot;,&quot;width&quot;:900}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/composants%20pc/m%c3%a9moire/corsair/vengeance%20black%20ddr5/visu001.jpg\" width=\"900\" height=\"600\"><figcaption class=\"attachment__caption\"></figcaption></figure></div><div><strong>Tout pour l\'overclocking<br></strong><br></div><div>La <strong>Vengeance </strong>est compatible avec le <strong>standard XMP .</strong> Enclenchez-le, et il s\'ajustera automatiquement à la vitesse adéquate. Il ne vous reste qu\'à profiter d\'une performance incroyable et fiable sans craindre pour vos données.</div>'),
(28, 2, 15, 3, '2024-02-07 13:18:08', 'Asus TUF VG249Q1A', '24\", IPS, 16:9, 1920 x 1080 (FHD), 1 ms, 165 Hz, FreeSync, HDMI/DisplayPort', '<div>L\'écran Asus TUF VG249Q1A est un écran optimisé pour les joueurs à la recherche de performances, plutôt orienté gaming. Cet écran est doté d\'une dalle IPS de 24 pouces en Full HD (1920 x 1080 pixels), afin de profiter d\'images de qualité. Il dispose d\'un taux de rafraîchissement élevé de 165 Hz pour un fluidité hors-norme. Il intègre également un temps de réponse très réduit à seulement 1 ms, pour une rapidité exceptionnelle. Ce moniteur gaming possède les technologies ELMB et la compatibilité FreeSync Premium pour une expérience de jeu optimisée et encore plus fluide. Il réunit toutes les caractéristiques nécessaires pour faire de vous un compétiteur hors-pair.</div>', '01HP1VFYAPXNCBR3PGG17TEJ27.webp', 169.95, 20, 0, '<div><strong>Un écran fluide et rapide<br></strong><br></div><div><strong>Un écran 23,8\", dalle IPS en 165 Hz et 1 ms<br></strong><br></div><div>L\'écran <strong>Asus TUF VG249Q1A </strong>a été conçu pour les gamers à la recherche de performance et de qualité visuelle. Cet écran <strong>Asus</strong> possède un taux de rafraîchissement <strong>165 Hz</strong> ainsi qu\'un temps de réponse ultra-rapide de seulement <strong>1 ms</strong>, afin de vous procurer une très bonne <strong>rapidité</strong> et <strong>fluidité</strong>. Il dispose d\'une <strong>dalle IPS</strong>, vous procurant des images de qualité avec de très belles couleurs, de <strong>23,8 pouces</strong> en <strong>Full HD</strong> (1920 x 1080 pixels). L\'écran Asus TUF <strong>VG249Q1A</strong>, vous offrira un avantage considérable sur vos adversaires afin de vous frayer un chemin vers la victoire.<br><br></div><div><strong>Un confort visuel<br></strong><br></div><div>Le moniteur gaming <strong>VG249Q1A</strong> intègre les technologies <strong>Flicker-Free</strong> et <strong>Ultra-Low Blue Light</strong> pour améliorer votre <strong>confort oculaire</strong>. En effet, le Flicker-Free permet de <strong>réduire le scintillement</strong> de l\'écran et ainsi réduira votre fatigue oculaire, afin de jouer pendant des sessions plus longues. L\'Ultra-Low blue Light permet quant à lui de <strong>réduire l\'émission de lumières bleues</strong> de l\'écran, nocives pour les yeux.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:420,&quot;url&quot;:&quot;https://media.ldlc.com/bo/images/matnet/fiches/p%c3%a9riph%c3%a9riques/ecrans/asus/ar202111030105/ar202111030105-texte1sized.jpg&quot;,&quot;width&quot;:600}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.ldlc.com/bo/images/matnet/fiches/p%c3%a9riph%c3%a9riques/ecrans/asus/ar202111030105/ar202111030105-texte1sized.jpg\" width=\"600\" height=\"420\"><figcaption class=\"attachment__caption\"></figcaption></figure></div><div><strong>Technologies ELMB et compatible AMD FreeSync Premium<br></strong><br></div><div>Ce moniteur signé Asus dispose de la technologie <strong>ELMB</strong> (Extreme Low Motion Blur) permettant de réduire les effets de flou de mouvement. Combinée avec la compatibilité <strong>FreeSync Premium</strong>, qui quant à elle permet d\'éliminer les effets de <strong>tearing </strong>(déchirures d\'images) et de <strong>stuttering </strong>(saccades d\'images), elles offrent des images claires et fluides afin de redécouvrir vos jeux favoris.<br><br></div><div><strong>Un design pratique et confort<br></strong><br></div><div>L\'écran <strong>Asus TUF VG249Q1A </strong>vous offrira la possibilité de modifier sa hauteur, son inclinaison ou même sa rotation, pour le positionner comme bon vous semble. Il est également possible de le faire pivoter à la verticale, pratique notamment en double écran lors de stream. Cet écran est équipé d\'un port <strong>HDMI</strong> ainsi que d\'un <strong>DisplayPort</strong> pour le brancher à votre PC.<br><br></div><div>Par ailleurs, cet écran dispose de la <strong>norme VESA</strong> (<strong>100 x 100 mm</strong>) permettant de le fixer à un bras ou à votre mur, pour un confort optimal !<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:480,&quot;url&quot;:&quot;https://media.ldlc.com/bo/images/matnet/fiches/p%c3%a9riph%c3%a9riques/ecrans/asus/ar202111030105/ar202111030105-texte2sized.jpg&quot;,&quot;width&quot;:600}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.ldlc.com/bo/images/matnet/fiches/p%c3%a9riph%c3%a9riques/ecrans/asus/ar202111030105/ar202111030105-texte2sized.jpg\" width=\"600\" height=\"480\"><figcaption class=\"attachment__caption\"></figcaption></figure><br><br></div>'),
(29, 3, 15, 3, '2024-02-07 13:31:03', 'Corsair XENEON 32UHD144-A', '32\", Fast IPS, 16:9, 3840 x 2160 (4K UHD), 1 ms (MPRT), 144 Hz, HDR, G-Sync/FreeSync, HDMI/DisplayPort/USB-C', '<div>Avec l\'écran Corsair XENEON 32UHD144-A, profitez d\'une grande dalle QLED performante, cadencée à 144 Hz de 32 pouces. Sa technologie 4K UHD et HDR 600 sont les garants d\'images éblouissantes et fidèles pour tous vos besoins !</div>', '01HP1W7JVFGF2RXGAFZ03BCGQC.webp', 899.95, 20, 0, '<div><strong>Un écran gaming aux couleurs éblouissantes !<br></strong><br></div><div><strong>Une dalle QLED 144 Hz 4K UHD !<br></strong><br></div><div>Cet <strong>écran XENEON 32UHD144-A </strong>dispode d\'une qualité d\'image exceptionnelle. Sa conception <strong>Quantum Dot et sa technologie de dalle Fast IPS assurent des couleurs sublimes, réalistes et naturelles pour une couverture sRGB remplie à 100% </strong>et une <strong>gamme chromatique DCI-P3</strong> de <strong>98%</strong> <strong>! </strong>La technologie Quantum Dot permet le filtrage du rétroéclairage LED à travers des millions de cristaux de tailles différentes pour améliorer la précision chromatique et la qualité de l\'image. Il en résulte des <strong>couleurs incroyablement riche</strong>, de véritables tons noirs et des contrastes plus que saisissants avec notamment l\'intégration du <strong>Display HDR 600</strong> (avec un pic de luminosité à 600 nits) permettant des effets de jeux saisissants et des couleurs éclatantes.</div><div><strong><br>Une compatibilité G-Sync et FreeSync au menu<br></strong><br></div><div>Grâce à la <strong>technologie QLED</strong>, le temps de réponse est excellent: <strong>1 milliseconde</strong> ! <strong>Cadencée à 144 Hz</strong>, cette dalle se dévoile comme comme rapide est saisissante !<br><br></div><div>Compatible <strong>NVIDIA G-Sync</strong> et certifiée <strong>AMD FreeSync Premium</strong>, c\'est l\'assurance d\'une image fluide sans déchirements en toutes circonstances, quelle que soit votre carte graphique.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:800,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/P%C3%A9riph%C3%A9riques/Ecrans/Corsair/AR202310230120/txt1(1).jpg&quot;,&quot;width&quot;:800}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/P%C3%A9riph%C3%A9riques/Ecrans/Corsair/AR202310230120/txt1(1).jpg\" width=\"800\" height=\"800\"><figcaption class=\"attachment__caption\"></figcaption></figure></div><div><strong>Un pied complet et pratique<br></strong><br></div><div>Un écran de cette qualité et courbable exige un <strong>pied particulièrement robuste et stable</strong>. Corsair répond présent avec un support réglable pour trouver votre position de visualisation idéale ! Ajustez rapidement et avec fluidité le panneau jusqu\'à la hauteur et l\'orientation de votre choix grâce à une ergonomie complète d\'inclinaison et de pivotement. De plus, le moniteur est compatible VESA 100x100 mm et comprends un système de gestion des câbles particulièrement efficace !<br><br></div><div>Côté connectique, ce moniteur dipose de <strong>deux ports HDMI 2.0, un connecteur DisplayPort 1.4 et un port DisplayPort 1.4 USB-C. </strong>Retrouvez également <strong>deux ports USB 3.1</strong> et un <strong>port USB 3.1 Type-C</strong> ainsi qu\'une connectique <strong>jack 3,5 mm</strong> directement accessibles.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:800,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/P%C3%A9riph%C3%A9riques/Ecrans/Corsair/AR202310230120/txt2(1).jpg&quot;,&quot;width&quot;:800}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/bo-images/fiches/P%C3%A9riph%C3%A9riques/Ecrans/Corsair/AR202310230120/txt2(1).jpg\" width=\"800\" height=\"800\"><figcaption class=\"attachment__caption\"></figcaption></figure></div>'),
(30, 2, 15, 3, '2024-02-07 13:52:40', 'Asus VP229HE', '21.5\", IPS, 16:9, 1920 x 1080 (FHD), 5 ms, 75 Hz, VGA/HDMI', '<div>Un moniteur gaming pour les petits budgets ? Asus répond présent avec ce modèle VP229HE. C\'est un écran en résolution Full HD équipé d\'une dalle IPS de 21,5 pouces avec une fréquence de rafraichissement de 75 Hz et un temps de réponse très rapide : seulement 5 ms. Profitez également des nouvelles technologies Flicker-Free, le mode anti lumière bleue et la fonction ASUS GamePlus.</div>', '01HP1XF4S1QS2B1VEDG0P0V5RW.webp', 119.95, 15, 0, '<div><strong>Un écran gaming 21,5 pouces à petit prix !<br></strong><br></div><div><strong>Un qualité d\'image supérieure en Full HD !<br></strong><br></div><div>L\'écran Asus VP229HE est équipé d\'une dalle IPS, ce qui lui permet d\'avoir un temps de réponse de <strong>5 </strong>ms pour détruire vos ennemis. Profitez une qualité d\'image supérieure grâce à sa diagonale de 21,5\" avec un résolution 1920 x 1080 px Full HD et la technologie SplendidPlus exclusive aux écrans Asus: elle permet d\'optimiser la luminosité et les contrastes. Avec sa fréquence de 75 Hz , votre écran VP229HE peut générer jusqu\'à 75 images par secondes ce qui en résulte une meilleure fluidité pour une précision accrue lors de vos combats. Dispose aussi de la technologie <strong>AMD FreeSync.<br></strong><br></div><div>Votre moniteur VP229HE s\'daptera parfaitement à votre setup grâce à son design classique et élégant. Profitez aussi de deux speakers stereo de 1,5 watts chacun. De plus, pour un gain de place sur votre bureau, vous avez la possibilité de monter votre écran Asus sur un bras en montage VESA 100 x 100 mm.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:283,&quot;url&quot;:&quot;https://media.materiel.net/oproducts/AR201807060040_d0.jpg&quot;,&quot;width&quot;:400}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/oproducts/AR201807060040_d0.jpg\" width=\"400\" height=\"283\"><figcaption class=\"attachment__caption\"></figcaption></figure></div><div><strong>Des technologies au service du joueur !<br></strong><br></div><div>Ce moniteur VP229HE intègre la fonction ASUS GamePlus avec touches de raccourcis pour<strong> Pointeur OSD</strong> (OSD Crosshair) et <strong>Chronomètre </strong>(Timer). Les joueurs pourront faire leur choix entre quatre Pointeurs différents pour s\'adapter à leur jeu en cours tandis que le Chronomètre les aidera à garder un œil sur le temps écoulé dans les jeux de stratégie en temps réel. Ces outils amélioreront les compétences des joueurs..<br><br></div><div>Deux technologies sont mises à votre service pour triompher : le flicker-free et le low blue-light. La première va atténuer le scintillement de l\'écran, tandis que la seconde va réduire la lumière bleue émise par l\'écran, responsable de la fatigue oculaire. Jouez plus longtemps et avec plus de confort !<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:230,&quot;url&quot;:&quot;https://media.materiel.net/oproducts/AR201807060040_d1.jpg&quot;,&quot;width&quot;:400}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/oproducts/AR201807060040_d1.jpg\" width=\"400\" height=\"230\"><figcaption class=\"attachment__caption\"></figcaption></figure></div>'),
(31, 11, 17, 3, '2024-02-07 14:05:00', 'Logitech K280e', 'Bureautique, Classique, Filaire, PC portable (Chiclet)', '<div>Si vous recherchez un clavier qui ne se prend pas la tête, qui rend la saisie agréable et de surcroît silencieuse, ne vous posez plus de questions, le clavier K280e de Logitech est votre homme !</div>', '01HP1Y5RJZH01DNCSHVW1NM0TC.webp', 26.95, 20, 0, '<div><strong>Le clavier Logitech robuste !<br></strong><br></div><div><strong>Touches plates et silencieuses<br></strong><br></div><div>Le clavier K280e est destiné à toute la famille. Ses touches plates rendent votre saisie confortable, rapide et silencieuse à la fois, renforcée par le un repose-poignets intégré. Ne dérangez plus ceux qui regardent la télévision dans la même pièce que vous lorsque vous travaillez sur votre ordinateur.<br><br></div><div><strong>Un clavier conçu pour durer<br></strong><br></div><div><em>\"Conçu pour durer\"</em> est en quelque sorte sa devise puisque en plus d\'être résistant aux éclaboussures et testé pour supporter jusqu\'à 10 millions de frappes, il est recouvert d\'un revêtement qui empêche l\'effacement des lettres sur le long terme. Utilisez ses touches de fonction afin d\'accéder directement à la calculatrice, à la fonction recherche ou encore à votre boîte mail.<br><br></div><div>Arborant un look moderne, ce clavier Logitech séduit rapidement.<figure data-trix-attachment=\"{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:350,&quot;url&quot;:&quot;https://media.materiel.net/oproducts/AR201604220037_d0.jpg&quot;,&quot;width&quot;:400}\" data-trix-content-type=\"image\" class=\"attachment attachment--preview\"><img src=\"https://media.materiel.net/oproducts/AR201604220037_d0.jpg\" width=\"400\" height=\"350\"><figcaption class=\"attachment__caption\"></figcaption></figure></div>');

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `civilite` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `pseudo` varchar(100) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `nom` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `email`, `roles`, `password`, `civilite`, `prenom`, `pseudo`, `is_verified`, `nom`) VALUES
(1, 'cedric.falda@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$SExTMCee1foPum4g0bw1AeN5zrfF0K.HQIfWQD9dMcTv4drdPQMm2', 'monsieur', 'Cédric', NULL, 1, 'FALDA'),
(5, 'aliev@test.com', '[]', '$2y$13$IjZ5575mCQELUB4qt6WKmuFvC.paZDgeOiuUPu/44PMvXl3UBYJyG', NULL, NULL, NULL, 1, NULL),
(6, 'test@test.com', '[]', '$2y$13$JlnU.TtOz/3NueDDhHGEyOOozfLDJD/nuYQUznR9KivFyRxjT55IK', NULL, NULL, NULL, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `caracteristique_technique`
--
ALTER TABLE `caracteristique_technique`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_497DD634DF25C577` (`categorie_parent_id`);

--
-- Indexes for table `marque`
--
ALTER TABLE `marque`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indexes for table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_29A5EC274827B9B2` (`marque_id`),
  ADD KEY `IDX_29A5EC27BCF5E72D` (`categorie_id`),
  ADD KEY `IDX_29A5EC27D639D323` (`categorie_principale_id`);

--
-- Indexes for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1D1C63B3E7927C74` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `caracteristique_technique`
--
ALTER TABLE `caracteristique_technique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `marque`
--
ALTER TABLE `marque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categorie`
--
ALTER TABLE `categorie`
  ADD CONSTRAINT `FK_497DD634DF25C577` FOREIGN KEY (`categorie_parent_id`) REFERENCES `categorie` (`id`);

--
-- Constraints for table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `FK_29A5EC274827B9B2` FOREIGN KEY (`marque_id`) REFERENCES `marque` (`id`),
  ADD CONSTRAINT `FK_29A5EC27BCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`),
  ADD CONSTRAINT `FK_29A5EC27D639D323` FOREIGN KEY (`categorie_principale_id`) REFERENCES `categorie` (`id`);
--
-- Database: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
