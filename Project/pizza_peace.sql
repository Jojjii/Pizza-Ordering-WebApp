-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2024 at 02:04 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pizza peace`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `CheckUsernameExists` (IN `p_username` VARCHAR(255), OUT `p_exists` BOOLEAN)   BEGIN
    DECLARE username_count INT;

    -- Count the occurrences of the given username in the users table
    SELECT COUNT(*) INTO username_count
    FROM `users`
    WHERE Username = p_username;

    -- Set the OUT parameter based on the count
    IF username_count > 0 THEN
        SET p_exists = TRUE;
    ELSE
        SET p_exists = FALSE;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CatID` int(11) NOT NULL,
  `CategoryName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CatID`, `CategoryName`) VALUES
(1, 'Pizza'),
(2, 'Calzone'),
(3, 'Beverage');

-- --------------------------------------------------------

--
-- Table structure for table `dealitemlist`
--

CREATE TABLE `dealitemlist` (
  `DealID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dealitemlist`
--

INSERT INTO `dealitemlist` (`DealID`, `ItemID`, `Quantity`) VALUES
(1, 1, 3),
(1, 2, 8),
(1, 3, 2),
(2, 4, 5),
(2, 5, 10),
(2, 6, 4),
(3, 7, 2),
(3, 8, 4),
(4, 1, 1),
(4, 4, 1),
(4, 7, 1),
(5, 1, 1),
(9, 1, 2),
(9, 2, 1),
(9, 4, 1),
(9, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `dealorderlist`
--

CREATE TABLE `dealorderlist` (
  `OrderID` int(11) NOT NULL,
  `DealID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dealorderlist`
--

INSERT INTO `dealorderlist` (`OrderID`, `DealID`, `Quantity`) VALUES
(108, 2, 2),
(109, 2, 1),
(121, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `deals`
--

CREATE TABLE `deals` (
  `DealID` int(11) NOT NULL,
  `DealName` varchar(255) NOT NULL,
  `Price` int(11) NOT NULL,
  `URL` varchar(255) NOT NULL,
  `Active` tinyint(1) NOT NULL,
  `StockCount` int(11) NOT NULL DEFAULT 999
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deals`
--

INSERT INTO `deals` (`DealID`, `DealName`, `Price`, `URL`, `Active`, `StockCount`) VALUES
(1, 'Deal 1', 20, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRF5hOuYWgxgP3IP9CJNOEPpvhN3LmmpyX6Mw&usqp=CAU', 1, 999),
(2, 'Deal 2', 30, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTGZPCgwUQbacwnvLL9MRCnYM6lYva5V5GjFg&usqp=CAU', 1, 999),
(3, 'Deal 3', 15, 'https://example.com/deal3', 0, 999),
(9, 'Deal 10', 50, 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAoHCBYVFRgVFhUYGRgZGBgcGBwcGBgaGBwYGBgaGRwYGBgcIzAmHCErIRgYJjgmKy8xNTU1HCQ7QDszPy40NTEBDAwMEA8QGhISHzQhISs0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0MTQxNDQ0NDQ0NDQ0NDQ0NDQ0NDQ0NDE0NDQ0NDQ0NP/AABEIARMAtwMBIgACEQEDEQH/', 0, 999);

-- --------------------------------------------------------

--
-- Table structure for table `details`
--

CREATE TABLE `details` (
  `DetailId` int(11) NOT NULL,
  `UserId` int(11) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `PhoneNumber` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `details`
--

INSERT INTO `details` (`DetailId`, `UserId`, `Address`, `PhoneNumber`) VALUES
(1, 1, 'FB Area Karimabad, Karachi', '+92 300 1234567'),
(2, 2, '76 Block 8, Hyedrabad', '+92 321 9876543'),
(6, 2, '76 Gulshan Hadeed Karachi', '+92 314215958'),
(14, 11, 'Krachi', '0317102912'),
(15, 11, 'bLOCK 17, Gulshan-e-hadeed', '03182328239'),
(16, 11, 'Block 19, Gulshan-e-Iqlbal', '+92 1823282391');

--
-- Triggers `details`
--
DELIMITER $$
CREATE TRIGGER `before_insert_details` BEFORE INSERT ON `details` FOR EACH ROW BEGIN
    DECLARE address_count INT;

    -- Count the number of addresses for the given user
    SELECT COUNT(*) INTO address_count
    FROM `details`
    WHERE UserId = NEW.UserId;

    -- Check if the user has less than 3 addresses
    IF address_count >= 3 THEN
        -- Raise an exception or return an error message
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'User cannot have more than 3 addresses';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `ItemID` int(11) NOT NULL,
  `CatID` int(11) DEFAULT NULL,
  `Name` varchar(255) NOT NULL,
  `Flavour` varchar(255) DEFAULT NULL,
  `StockCount` int(11) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `Des` text DEFAULT NULL,
  `URL` varchar(255) DEFAULT NULL,
  `active` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`ItemID`, `CatID`, `Name`, `Flavour`, `StockCount`, `Price`, `Des`, `URL`, `active`) VALUES
(1, 1, 'Margherita Pizza', 'Classic', 50, 9.99, 'Delicious classic Margherita pizza', 'https://static.toiimg.com/thumb/53110049.cms?width=1200&height=900', 1),
(2, 1, 'Cheese Pizza', 'Spicy', 30, 11.99, 'Tasty pepperoni pizza with extra cheese', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRtwc5ofUxzM2eLBt0WNos6kAJnkqo3RTJvlQ&usqp=CAU', 1),
(3, 2, 'Vegetarian Calzone', 'Mushroom and Cheese', 20, 8.99, 'Calzone filled with fresh vegetables and cheese', 'https://hips.hearstapps.com/hmg-prod/images/190130-calzone-horizontal-2-1549421238.png?crop=1xw:0.843328335832084xh;center,top', 1),
(4, 3, 'Cola', 'Regular', 100, 1.99, 'Classic cola drink', 'https://www.alfatah.pk/cdn/shop/products/42117131_20cb0b35-f10b-4e25-b3e7-9060f84267aa_1024x1024.jpg?v=1679570074', 1),
(5, 3, 'Orange Juice', 'Freshly Squeezed', 40, 3.49, 'Refreshing orange juice', 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?q=80&w=1000&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8ZnJ1aXQlMjBqdWljZXxlbnwwfHwwfHx8MA%3D%3D', 1),
(6, 1, 'Pepperoni Pizza', 'Pepperoni', 50, 12.99, 'Classic pepperoni pizza', 'https://www.allrecipes.com/thmb/iXKYAl17eIEnvhLtb4WxM7wKqTc=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/240376-homemade-pepperoni-pizza-Beauty-3x4-1-6ae54059c23348b3b9a703b6a3067a44.jpg', 1),
(7, 1, 'Hawaian Pizza', 'Margherita', 40, 10.99, 'Traditional Margherita pizza', 'https://cdn.loveandlemons.com/wp-content/uploads/2023/07/margherita-pizza.jpg', 1),
(8, 1, 'Supreme Pizza', 'Supreme', 35, 14.99, 'Loaded with various toppings', 'https://hips.hearstapps.com/hmg-prod/images/homemade-pizza-index-1591135484.jpg?crop=1xw:0.7056962025316456xh;center,top&resize=1200:*', 1),
(9, 2, 'Parma Ham & Mozzarella Calzone', 'Parma Ham & Mozzarella', 30, 15.99, 'Delicious calzone with Parma ham and mozzarella', 'https://images.immediate.co.uk/production/volatile/sites/30/2020/08/parma-ham-mozzarella-calzone-4ad2f4a.jpg?resize=768,574', 1),
(10, 2, 'Pepperoni Calzone', 'Pepperoni', 25, 13.99, 'Calzone filled with pepperoni and cheese', 'https://www.lanascooking.com/wp-content/uploads/2021/10/quick-and-easy-pepperoni-calzone-feature-1200x1200-1.jpg', 1),
(12, 3, 'Mango Smoothie', NULL, 40, 4.99, 'Healthy mango smoothie', 'https://cdn.loveandlemons.com/wp-content/uploads/2023/05/mango-smoothie.jpg', 1),
(13, 3, 'Strawberry Lemonade', NULL, 45, 3.99, 'Sweet strawberry lemonade', 'https://hips.hearstapps.com/hmg-prod/images/delish-how-to-make-a-smoothie-horizontal-1542310071.png?crop=0.8893333333333334xw:1xh;center,top&resize=1200:*', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orderlist`
--

CREATE TABLE `orderlist` (
  `OrderID` int(11) DEFAULT NULL,
  `ItemID` int(11) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderlist`
--

INSERT INTO `orderlist` (`OrderID`, `ItemID`, `Quantity`) VALUES
(14, 8, 2),
(14, 7, 3),
(15, 2, 1),
(15, 1, 1),
(15, 6, 1),
(15, 8, 1),
(16, 2, 1),
(16, 1, 1),
(16, 6, 1),
(16, 8, 1),
(26, 1, 1),
(26, 2, 1),
(26, 13, 1),
(28, 1, 2),
(28, 6, 1),
(28, 5, 1),
(29, 6, 1),
(30, 5, 2),
(31, 5, 2),
(32, 5, 2),
(33, 5, 2),
(34, 5, 2),
(35, 5, 2),
(36, 5, 2),
(37, 5, 2),
(38, 5, 2),
(39, 5, 2),
(40, 5, 2),
(41, 5, 2),
(42, 5, 2),
(43, 5, 2),
(44, 5, 2),
(45, 5, 2),
(46, 5, 2),
(47, 5, 2),
(48, 5, 2),
(49, 5, 2),
(50, 5, 2),
(51, 5, 2),
(52, 5, 2),
(53, 2, 1),
(54, 2, 1),
(55, 2, 1),
(56, 2, 1),
(57, 2, 1),
(58, 2, 1),
(59, 2, 1),
(60, 2, 1),
(61, 2, 1),
(62, 2, 1),
(63, 2, 1),
(64, 2, 1),
(65, 2, 1),
(66, 2, 1),
(67, 2, 1),
(68, 2, 1),
(69, 2, 1),
(70, 2, 1),
(71, 2, 1),
(72, 2, 1),
(73, 2, 1),
(74, 2, 1),
(75, 2, 1),
(76, 6, 1),
(77, 2, 1),
(78, 2, 1),
(79, 2, 1),
(80, 2, 1),
(81, 2, 1),
(82, 2, 1),
(83, 1, 1),
(84, 2, 1),
(85, 2, 1),
(86, 1, 1),
(91, 2, 1),
(91, 6, 1),
(92, 2, 1),
(93, 2, 3),
(93, 1, 1),
(94, 2, 1),
(95, 2, 1),
(95, 9, 1),
(98, 2, 1),
(109, 1, 1),
(109, 9, 1),
(110, 2, 1),
(111, 2, 1),
(112, 2, 1),
(113, 2, 1),
(114, 2, 1),
(115, 2, 1),
(116, 2, 1),
(117, 6, 1),
(118, 2, 1),
(119, 6, 1),
(120, 2, 1),
(122, 6, 1),
(123, 2, 5),
(123, 6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `DetailID` int(11) DEFAULT NULL,
  `DateTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `TotalPrice` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `UserID`, `DetailID`, `DateTime`, `TotalPrice`) VALUES
(14, 2, 2, '2023-11-29 11:56:43', 62.95),
(15, 1, 1, '2023-12-01 21:26:53', 49.96),
(16, 1, 1, '2023-12-01 21:26:59', 49.96),
(26, 11, 14, '2023-12-05 01:41:37', 25.97),
(28, 11, 14, '2023-12-06 18:36:47', 36.46),
(29, 11, 14, '2023-12-06 20:43:13', 42.99),
(30, 11, 14, '2023-12-07 04:47:43', 6.98),
(31, 11, 14, '2023-12-07 04:47:45', 6.98),
(32, 11, 14, '2023-12-07 04:47:46', 6.98),
(33, 11, 14, '2023-12-07 04:47:47', 6.98),
(34, 11, 14, '2023-12-07 04:47:47', 6.98),
(35, 11, 14, '2023-12-07 04:47:47', 6.98),
(36, 11, 14, '2023-12-07 04:47:48', 6.98),
(37, 11, 14, '2023-12-07 04:47:48', 6.98),
(38, 11, 14, '2023-12-07 04:47:49', 6.98),
(39, 11, 14, '2023-12-07 04:47:50', 6.98),
(40, 11, 14, '2023-12-07 04:47:51', 6.98),
(41, 11, 14, '2023-12-07 04:47:51', 6.98),
(42, 11, 14, '2023-12-07 04:47:51', 6.98),
(43, 11, 14, '2023-12-07 04:47:51', 6.98),
(44, 11, 14, '2023-12-07 04:47:52', 6.98),
(45, 11, 14, '2023-12-07 04:47:54', 6.98),
(46, 11, 14, '2023-12-07 04:47:55', 6.98),
(47, 11, 14, '2023-12-07 04:47:55', 6.98),
(48, 11, 14, '2023-12-07 04:47:55', 6.98),
(49, 11, 14, '2023-12-07 04:47:55', 6.98),
(50, 11, 14, '2023-12-07 04:47:55', 6.98),
(51, 11, 14, '2023-12-07 04:47:56', 6.98),
(52, 11, 14, '2023-12-07 04:47:56', 6.98),
(53, 11, 14, '2023-12-07 04:48:08', 11.99),
(54, 11, 14, '2023-12-07 04:48:09', 11.99),
(55, 11, 14, '2023-12-07 04:48:09', 11.99),
(56, 11, 14, '2023-12-07 04:48:09', 11.99),
(57, 11, 14, '2023-12-07 04:48:09', 11.99),
(58, 11, 14, '2023-12-07 04:48:09', 11.99),
(59, 11, 14, '2023-12-07 04:48:09', 11.99),
(60, 11, 14, '2023-12-07 04:48:10', 11.99),
(61, 11, 14, '2023-12-07 04:48:10', 11.99),
(62, 11, 14, '2023-12-07 04:48:10', 11.99),
(63, 11, 14, '2023-12-07 04:48:10', 11.99),
(64, 11, 14, '2023-12-07 04:48:10', 11.99),
(65, 11, 14, '2023-12-07 04:49:44', 11.99),
(66, 11, 14, '2023-12-07 04:57:59', 11.99),
(67, 11, 14, '2023-12-07 05:01:31', 11.99),
(68, 11, 14, '2023-12-07 05:02:20', 11.99),
(69, 11, 14, '2023-12-07 05:02:49', 11.99),
(70, 11, 14, '2023-12-07 05:02:50', 11.99),
(71, 11, 14, '2023-12-07 05:02:51', 11.99),
(72, 11, 14, '2023-12-07 05:02:51', 11.99),
(73, 11, 14, '2023-12-07 05:03:15', 11.99),
(74, 11, 14, '2023-12-07 05:04:35', 11.99),
(75, 11, 14, '2023-12-07 05:07:12', 11.99),
(76, 11, 14, '2023-12-07 05:11:47', 12.99),
(77, 11, 14, '2023-12-07 05:14:33', 11.99),
(78, 11, 14, '2023-12-07 05:15:22', 11.99),
(79, 11, 14, '2023-12-07 05:16:04', 11.99),
(80, 11, 14, '2023-12-07 05:17:13', 11.99),
(81, 11, 14, '2023-12-07 05:17:33', 11.99),
(82, 11, 14, '2023-12-07 05:18:12', 11.99),
(83, 11, 14, '2023-12-07 05:18:56', 9.99),
(84, 11, 14, '2023-12-07 05:20:10', 11.99),
(85, 11, 14, '2023-12-07 05:21:55', 11.99),
(86, 11, 14, '2023-12-07 05:23:13', 9.99),
(87, 11, 14, '2023-12-07 05:34:33', 30.00),
(88, 11, 14, '2023-12-07 05:36:10', 30.00),
(89, 11, 14, '2023-12-07 05:38:01', 30.00),
(90, 11, 14, '2023-12-07 05:40:48', 30.00),
(91, 11, 14, '2023-12-07 05:43:34', 24.98),
(92, 11, 14, '2023-12-07 05:48:07', 11.99),
(93, 11, 14, '2023-12-07 06:01:40', 45.96),
(94, 11, 14, '2023-12-07 06:15:56', 11.99),
(95, 11, 14, '2023-12-07 06:19:33', 27.98),
(96, 11, 14, '2023-12-07 06:20:49', 30.00),
(97, 11, 14, '2023-12-07 06:23:46', 30.00),
(98, 11, 14, '2023-12-07 06:25:31', 11.99),
(99, 11, 14, '2023-12-07 06:27:17', 30.00),
(100, 11, 14, '2023-12-07 06:34:13', 20.00),
(101, 11, 14, '2023-12-07 06:34:46', 30.00),
(102, 11, 14, '2023-12-07 06:35:32', 50.00),
(103, 11, 14, '2023-12-07 06:37:35', 30.00),
(104, 11, 14, '2023-12-07 06:38:20', 30.00),
(105, 11, 14, '2023-12-07 06:40:46', 30.00),
(106, 11, 14, '2023-12-07 06:41:34', 20.00),
(107, 11, 14, '2023-12-07 06:43:46', 30.00),
(108, 11, 14, '2023-12-07 06:45:00', 60.00),
(109, 11, 14, '2023-12-07 06:48:48', 55.98),
(110, 11, 14, '2023-12-07 06:55:48', 11.99),
(111, 11, 14, '2023-12-07 06:57:39', 11.99),
(112, 11, 14, '2023-12-07 06:58:58', 11.99),
(113, 11, 14, '2023-12-07 06:59:52', 11.99),
(114, 11, 14, '2023-12-07 07:00:21', 11.99),
(115, 11, 14, '2023-12-07 07:01:34', 11.99),
(116, 11, 14, '2023-12-07 07:03:33', 11.99),
(117, 11, 14, '2023-12-07 07:07:12', 12.99),
(118, 11, 14, '2023-12-07 07:08:07', 11.99),
(119, 11, 14, '2023-12-07 07:17:09', 12.99),
(120, 11, 14, '2023-12-07 07:18:58', 11.99),
(121, 11, 14, '2023-12-07 07:20:10', 30.00),
(122, 11, 14, '2023-12-07 07:43:15', 12.99),
(123, 11, 14, '2023-12-07 08:04:30', 85.93);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Privileges` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Name`, `Username`, `Password`, `Privileges`) VALUES
(1, 'John Doe', 'john_doe', '123', 1),
(2, 'Jane Smith', 'jane_smith', '123', 0),
(8, 'Daniyal', 'khan', 'daniyal', 0),
(9, 'Dniyal Khan', 'daniyalgopang', '1100', 1),
(11, 'Junaid Sayani', 'junaidsayani', '12345', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CatID`);

--
-- Indexes for table `deals`
--
ALTER TABLE `deals`
  ADD PRIMARY KEY (`DealID`);

--
-- Indexes for table `details`
--
ALTER TABLE `details`
  ADD PRIMARY KEY (`DetailId`),
  ADD KEY `UserId` (`UserId`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `CatID` (`CatID`);

--
-- Indexes for table `orderlist`
--
ALTER TABLE `orderlist`
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `ItemID` (`ItemID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `DetailID` (`DetailID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `deals`
--
ALTER TABLE `deals`
  MODIFY `DealID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `details`
--
ALTER TABLE `details`
  MODIFY `DetailId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `ItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `details`
--
ALTER TABLE `details`
  ADD CONSTRAINT `details_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`CatID`) REFERENCES `categories` (`CatID`);

--
-- Constraints for table `orderlist`
--
ALTER TABLE `orderlist`
  ADD CONSTRAINT `orderlist_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`),
  ADD CONSTRAINT `orderlist_ibfk_2` FOREIGN KEY (`ItemID`) REFERENCES `items` (`ItemID`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`DetailID`) REFERENCES `details` (`DetailId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
