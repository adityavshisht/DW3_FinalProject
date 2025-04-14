-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2025 at 06:36 AM
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
-- Database: `retechx`
--

-- --------------------------------------------------------

--
-- Table: categories
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table categories
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Phone'),
(2, 'Laptop'),
(3, 'Tablet'),
(4, 'Smartwatch'),
(5, 'Headphones'),
(6, 'Keyboards'),
(7, 'Computer Accessories');

-- --------------------------------------------------------

--
-- Table: orders
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `total_price` decimal(10,2) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL,
  `delivery_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table orders
--

INSERT INTO `orders` (`order_id`, `buyer_id`, `product_id`, `seller_id`, `order_date`, `total_price`, `payment_status`, `delivery_status`) VALUES
(1, 2, 1, 1, '2025-03-27 20:32:07', 499.99, 'Paid', 'Delivered'),
(2, 1, 2, 2, '2025-03-27 20:32:07', 899.00, 'Pending', 'Shipped'),
(3, 6, 2, 2, '2025-04-04 00:12:24', 899.00, 'Paid', NULL),
(4, 7, 2, 2, '2025-04-04 00:23:10', 899.00, 'Paid', NULL);

-- --------------------------------------------------------

--
-- Table: payments
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_date` datetime DEFAULT current_timestamp(),
  `payment_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table payments
--

INSERT INTO `payments` (`payment_id`, `order_id`, `buyer_id`, `payment_method`, `transaction_id`, `payment_date`, `payment_status`) VALUES
(1, 1, 2, 'Credit Card', 'TXN123456', '2025-03-27 20:32:07', 'Success'),
(2, 2, 1, 'PayPal', 'TXN987654', '2025-03-27 20:32:07', 'Pending');

-- --------------------------------------------------------

--
-- Table: pickup_slots
--

CREATE TABLE `pickup_slots` (
  `slot_id` int(11) DEFAULT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `slot_date` date DEFAULT NULL,
  `slot_time` time DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table pickup_slots
--

INSERT INTO `pickup_slots` (`slot_id`, `seller_id`, `slot_date`, `slot_time`, `status`) VALUES
(2, 1, '2025-04-17', '10:30:00', 'Confirmed'),
(3, 2, '2025-04-15', '13:00:00', 'Confirmed'),
(1, 3, '2025-04-14', '14:00:00', 'Confirmed'),
(4, 4, '2025-04-22', '15:00:00', 'Confirmed'),
(1, 5, '2025-04-13', '14:00:00', 'Confirmed'),
(3, 6, '2025-04-16', '13:00:00', 'Confirmed');

-- --------------------------------------------------------

--
-- Table: products
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `specifications` text DEFAULT NULL,
  `condition` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table products
--
INSERT INTO `products` (`product_id`, `user_id`, `category_id`, `title`, `brand`, `model`, `specifications`, `condition`, `price`, `status`, `created_at`, `image`) VALUES
(1, 1, 1, 'iPhone 12', 'Apple', 'A2403', '128GB, Black', 'Good', 499.99, 'Available', '2025-03-27 20:32:07', 'iphone12.jpg'),
(2, 2, 2, 'Dell XPS 13', 'Dell', '9380', 'i7, 16GB RAM, 512GB SSD', 'Very Good', 899.00, 'Available', '2025-03-27 20:32:07', 'laptop1.jpg'),
(3, 1, 5, 'Sony WH-1000XM4', 'Sony', 'WH-1000XM4', 'Noise Cancelling Headphones', 'Like New', 250.00, 'Available', '2025-03-27 20:32:07', 'headphones1.jpg'),
(4, 3, 4, 'Apple Watch Series 7', 'Apple', 'Series 7', 'GPS, 41mm, Midnight Aluminum Case', 'New', 399.00, 'Available', '2025-04-04 10:00:00', 'applewatch.jpg'),
(5, 4, 3, 'Samsung Galaxy Tab S7', 'Samsung', 'SM-T870', '11-inch, 128GB, Mystic Black', 'Very Good', 649.99, 'Available', '2025-04-04 10:30:00', 'galaxytab7.jpg'),
(6, 5, 2, 'HP Spectre x360', 'HP', '14-ea0023dx', 'i7, 16GB RAM, 1TB SSD', 'Like New', 1249.99, 'Available', '2025-04-04 11:00:00', 'hpspectrex360.jpg'),
(7, 6, 6, 'Logitech MX Master 3', 'Logitech', 'MX Master 3', 'Advanced Wireless Mouse', 'New', 99.99, 'Available', '2025-04-04 11:30:00', 'logitechmx3.jpg'),
(8, 7, 1, 'Google Pixel 5', 'Google', 'Pixel 5', '128GB, Just Black', 'Good', 699.00, 'Available', '2025-04-04 12:00:00', 'pixel5.jpg'),
(9, 8, 3, 'Microsoft Surface Pro 7', 'Microsoft', 'Pro 7', 'i5, 8GB RAM, 128GB SSD', 'Very Good', 749.99, 'Available', '2025-04-04 12:30:00', 'surfacepro7.jpg'),
(10, 1, 3, 'iPad Pro 12.9', 'Apple', '5th Gen', '256GB, Space Gray', 'New', 1099.00, 'Available', '2025-04-04 13:00:00', 'ipadpro.jpg');


-- --------------------------------------------------------

--
-- Table: reviews
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table reviews
--

INSERT INTO `reviews` (`review_id`, `user_id`, `product_id`, `rating`, `comment`, `created_at`) VALUES
(1, 2, 1, 5, 'Great condition, fast delivery!', '2025-03-27 20:32:07'),
(2, 1, 2, 4, 'Laptop is good but battery could be better', '2025-03-27 20:32:07'),
(3, 2, 3, 5, 'Amazing sound quality!', '2025-03-27 20:32:07');

-- --------------------------------------------------------

--
-- Table: seller_info
--

CREATE TABLE `seller_info` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `preferred_date` date NOT NULL,
  `preferred_time` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table seller_info
--

INSERT INTO `seller_info` (`id`, `name`, `email`, `phone`, `address`, `preferred_date`, `preferred_time`, `created_at`) VALUES
(1, 'Shaillaja Ravi', 'shai@gmail.com', '5023456788', '2175 Blvd de Maisonneuve ouest\r\n701', '2025-04-17', '11:00-13:00', '2025-04-03 22:46:36'),
(2, 'Aditya Sharma', 'aditya@yahoo.ca', '2345678766', '213,2125\r\nRUE SAINT MARC', '2025-04-15', '13:00-15:00', '2025-04-03 22:50:05'),
(3, 'Kishore S', 'kishore21@gmail.com', '2345678908', '345,OldPort', '2025-04-14', '9:00-11:00', '2025-04-03 23:51:36'),
(4, 'Srikar S', 'sri@hotmail.com', '4356789090', '234, Blvd Parc', '2025-04-22', '15:00-17:00', '2025-04-03 23:58:02'),
(5, 'Riya Manoj', 'riyamanoj@yahoo.com', '5064356789', '515, Sherbrooke', '2025-04-13', '9:00-11:00', '2025-04-04 00:07:20'),
(6, 'Aditya Sharma', 'aditya01@hotmail.com', '5064537890', '320, Oldport', '2025-04-16', '13:00-15:00', '2025-04-04 00:21:14');

-- --------------------------------------------------------

--
-- Table: users
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `user_type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table users
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `phone`, `address`, `user_type`) VALUES
(1, 'Shaillaja Ravi', 'shai@gmail.com', '$2y$10$YQdD5pnAKk5zKP5DKy0rju6rnxJ3SjoqRBhKaTaJTbzPYEO3zWV2K', '123-456-7890', '123 Maple St', 'user'),
(2, 'Bob Johnson', 'bob@example.com', '$2y$10$z48NO/cHQfbM8IfNaawU7OZ/xpvhfXpcZZ4jcOq3DCIu1TPxUDivS', '987-654-3210', '456 Oak Ave', 'user'),
(3, 'Admin User', 'admin@example.com', '$2y$10$3fbgN0/jn4Hryzi8VDFUVu4vWXPGThTefb9FDzUYa9kgPZKPlqH0e', '111-222-3333', '789 Admin Blvd', 'admin'),
(4, 'adi@proj25', 'adi@proj25', 'YzGUT0CRE8se0WDNgnXGesypMT39YUm', NULL, NULL, 'user'),
(5, 'harman@proj25', 'harman@proj25', '$2y$10$fGJPO56UI2KPsHZ3pW0Hg.YzGUT0CRE8se0WDNgnXGesypMT39YUm', NULL, NULL, 'user'),
(6, 'Riya M', 'riyamanoj@yahoo.com', '$2y$10$Ff5gv3tGRh8GZOdy8fuAPeoPxu3nI/lDj//ZuQwNnEKIUhqkVAFze', NULL, NULL, 'user'),
(7, 'Aditya Sharma', 'aditya01@hotmail.com', '$2y$10$Zv35XXxVaP315lHsAS5kf.MAi3jt3nVeUFgBlS9JBOnGd/djMcUGq', NULL, NULL, 'user'),
(8, 'Sankini', 'sankini@gmail.com', '$2y$10$qQvt1/T/7mHTsIW1pyZKYONtk2KWh4NOC5IALmEeEz7obWnY6Og.y', NULL, NULL, 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table categories
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table orders
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table payments
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `buyer_id` (`buyer_id`);

--
-- Indexes for table pickup_slots
--
ALTER TABLE `pickup_slots`
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table products
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table reviews
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table seller_info
--
ALTER TABLE `seller_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table users
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table categories
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table orders
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table payments
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table products
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table reviews
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table seller_info
--
ALTER TABLE `seller_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table users
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table orders
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`seller_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table payments
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table pickup_slots
--
ALTER TABLE `pickup_slots`
  ADD CONSTRAINT `pickup_slots_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `seller_info` (`id`);

--
-- Constraints for table products
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table reviews
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
