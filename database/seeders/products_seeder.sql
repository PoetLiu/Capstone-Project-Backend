-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2024 at 02:47 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

USE `PrimeMart`;
INSERT INTO `products` (`brand`, `name`, `description`, `specifications`, `price`, `onsale_price`, `stock`, `is_featured`, `category_id`, `image_url`, `created_at`, `updated_at`) VALUES
('Organic Farms', 'Apple', 'Fresh organic apples.', 'Red, sweet, and crispy', 1.50, NULL, 200, 0, 1, 'products/apple.jpg', '2024-11-23 01:09:46', '2024-11-23 01:09:46'),
('Tropical Fresh', 'Banana', 'Sweet bananas from Ecuador.', 'Rich in potassium', 0.50, NULL, 300, 0, 1, 'products/banana.jpg', '2024-11-23 01:09:46', '2024-11-23 01:09:46'),
('Green Valley', 'Carrot', 'Crunchy and fresh carrots.', 'High in Vitamin A', 1.20, NULL, 150, 0, 2, 'products/carrot.jpg', '2024-11-23 01:09:46', '2024-11-23 01:09:46'),
('Local Farms', 'Potato', 'Locally grown potatoes.', 'Good for baking and frying', 0.80, NULL, 400, 0, 2, 'products/potato.jpg', '2024-11-23 01:09:46', '2024-11-23 01:09:46'),
('Dairy Delight', 'Milk', 'Pure cow milk.', '500ml, pasteurized', 2.00, NULL, 100, 0, 3, 'products/milk.jpg', '2024-11-23 01:09:46', '2024-11-23 01:09:46'),
('Cheese Lovers', 'Cheddar Cheese', 'Aged cheddar cheese.', 'Rich and creamy', 5.00, NULL, 50, 0, 3, 'products/cheddar_cheese.jpg', '2024-11-23 01:09:46', '2024-11-23 01:09:46'),
('Café Fresh', 'Coffee', 'Premium roasted coffee beans.', 'Rich aroma', 10.00, NULL, 200, 0, 4, 'products/coffee.jpg', '2024-11-23 01:09:46', '2024-11-23 01:09:46'),
('Tea Time', 'Green Tea', 'Healthy green tea bags.', '25 bags per box', 8.00, NULL, 300, 0, 4, 'products/green_tea.jpg', '2024-11-23 01:09:46', '2024-11-23 01:09:46'),
('Crunchy Snacks', 'Potato Chips', 'Crispy and salty chips.', 'Family size pack', 3.50, NULL, 250, 0, 5, 'products/potato_chips.jpg', '2024-11-23 01:09:46', '2024-11-23 01:09:46'),
('Sweet Bites', 'Chocolate Cookies', 'Delicious chocolate chip cookies.', 'Pack of 10', 4.00, NULL, 100, 0, 5, 'products/chocolate_cookies.jpg', '2024-11-23 01:09:46', '2024-11-23 01:09:46'),
('Bread Basket', 'Whole Wheat Bread', 'Freshly baked whole wheat bread.', '500g loaf', 2.50, NULL, 80, 0, 6, 'products/whole_wheat_bread.jpg', '2024-11-23 01:09:46', '2024-11-23 01:09:46'),
('Pastry House', 'Croissant', 'Buttery croissants.', 'Pack of 4', 5.00, NULL, 40, 0, 6, 'products/croissant.jpg', '2024-11-23 01:09:46', '2024-11-23 01:09:46'),
('Golden Grains', 'Rice', 'Premium basmati rice.', '1kg pack', 3.00, NULL, 500, 0, 7, 'products/rice.jpg', '2024-11-23 01:09:46', '2024-11-23 01:09:46'),
('Healthy Harvest', 'Quinoa', 'Organic quinoa seeds.', '500g pack', 6.00, NULL, 150, 0, 7, 'products/quinoa.jpg', '2024-11-23 01:09:46', '2024-11-23 01:09:46'),
('Fresh Cuts', 'Chicken Breast', 'Boneless chicken breast.', '500g pack', 7.50, NULL, 100, 0, 8, 'products/chicken_breast.jpg', '2024-11-23 01:09:46', '2024-11-23 01:09:46'),
('Prime Meat', 'Lamb Chops', 'Fresh lamb chops.', '500g pack', 15.00, NULL, 50, 0, 8, 'products/lamb_chops.jpg', '2024-11-23 01:09:46', '2024-11-23 01:09:46'),
('Ocean Fresh', 'Salmon', 'Fresh Atlantic salmon.', '500g fillet', 12.00, NULL, 80, 0, 9, 'products/salmon.jpg', '2024-11-23 01:09:46', '2024-11-23 01:09:46'),
('Seafood Market', 'Shrimp', 'Frozen shrimp.', '1kg pack', 18.00, NULL, 40, 0, 9, 'products/shrimp.jpg', '2024-11-23 01:09:46', '2024-11-23 01:09:46'),
('Quick Bites', 'Pizza', 'Frozen margherita pizza.', 'Large size', 7.00, NULL, 60, 0, 10, 'products/pizza.jpg', '2024-11-23 01:09:46', '2024-11-23 01:09:46'),
('Frozen Treats', 'Ice Cream', 'Vanilla ice cream.', '1L tub', 5.50, NULL, 80, 0, 10, 'products/ice_cream.jpg', '2024-11-23 01:09:46', '2024-11-23 06:15:07');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
