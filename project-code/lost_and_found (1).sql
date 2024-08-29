-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2024 at 10:29 AM
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
-- Database: `lost_and_found`
--

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `feedback_text` text DEFAULT NULL,
  `feedback_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_categories`
--

CREATE TABLE `item_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_categories`
--

INSERT INTO `item_categories` (`category_id`, `category_name`) VALUES
(1, 'Watches'),
(2, 'Phones'),
(3, 'Keys'),
(4, 'Laptops'),
(5, 'Books');

-- --------------------------------------------------------

--
-- Table structure for table `lost_items`
--

CREATE TABLE `lost_items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `item_description` text DEFAULT NULL,
  `date_lost` datetime NOT NULL DEFAULT current_timestamp(),
  `location` varchar(100) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `status` enum('Lost','Found') NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `item_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lost_items`
--

INSERT INTO `lost_items` (`item_id`, `item_name`, `item_description`, `date_lost`, `location`, `owner_id`, `status`, `category_id`, `item_image`) VALUES
(1, 'Tecno WX3P', 'A Tecno WX3P with a golden back and cracked screen', '2023-11-02 06:11:06', 'Science Complex', 8, 'Lost', 2, 'tecno-wx3p.jpg'),
(2, 'Wristwatch', 'A golden wristwatch with scratches on the face and a red belt', '2023-11-03 14:00:18', 'Cafeteria', 1, 'Lost', 1, 'y7qvu_512.webp'),
(11, 'Meme', 'Mememememe', '2023-11-20 09:05:52', 'MEme', 3, 'Lost', 1, '9b1c581570f642999aae2056a09c1f94.jpg'),
(12, 'Car Key', 'A black lexus remote control car key', '2023-11-20 09:24:11', 'Predegree hall', 8, 'Found', 3, '1.jpg'),
(13, 'hbwehl', 'iwueh', '2023-11-20 12:24:59', 'JLAN', 3, 'Lost', 2, 'tuto-attache-clefs11.jpg'),
(16, 'Wristwatch', 'ss', '2023-12-01 01:04:41', 'ss', 3, 'Lost', 1, 'images.jfif'),
(23, 'rr', 'rrrr', '2023-12-05 02:51:49', 'r', 1, 'Found', 1, '41cf6ceafd3ba70fb85c308d045301d1.jpg'),
(24, 'Car Key', ' w', '2024-01-09 23:27:24', 'Cafeteria', 2, 'Lost', 3, '40123e92bc95f53a0c4272128efc2a3b.jpg'),
(26, 'Wristwatch', 't4utrierueies', '2024-01-15 12:49:25', 'mph33', 8, 'Lost', 4, '3ed674e1a1c174152f3edc59e53a2fa6.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `private_messages`
--

CREATE TABLE `private_messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message_text` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `private_messages`
--

INSERT INTO `private_messages` (`message_id`, `sender_id`, `receiver_id`, `message_text`, `timestamp`) VALUES
(1, 1, 2, 'Afa bro wassup', '2023-11-06 02:57:30'),
(2, 2, 1, 'I dey o, u?', '2023-11-06 02:57:30'),
(3, 3, 2, 'Sup bro', '2023-11-13 02:47:34'),
(4, 2, 3, 'I dey', '2023-11-13 03:27:16'),
(7, 1, 2, 'E de work o', '2023-11-14 02:05:04'),
(32, 2, 1, 'Haha', '2023-11-14 09:22:44');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `sender_id`, `receiver_id`, `item_id`, `status`, `transaction_date`) VALUES
(18, 1, 3, 1, 'successful', '2023-11-17 02:58:02'),
(23, 3, 8, 1, 'successful', '2024-01-09 23:16:52'),
(24, 2, 3, 24, 'pending', '2024-01-09 23:17:31'),
(25, 2, 3, 24, 'pending', '2024-01-09 23:17:39'),
(26, 2, 8, 1, 'successful', '2024-01-09 23:19:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `type` varchar(5) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `matriculation_number` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `verification_status` text DEFAULT 'unverified',
  `account_status` varchar(10) DEFAULT 'enabled',
  `verification_document` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `type`, `first_name`, `middle_name`, `last_name`, `email`, `phone_number`, `gender`, `matriculation_number`, `password`, `profile_picture`, `verification_status`, `account_status`, `verification_document`) VALUES
(1, 'admin', 'Godwin', 'Adakonye', 'John', 'gj09042003@gmail.com', '08142659673', 'Male', 'PAS/CSC/19/007', '$2y$10$Dm0l1iFPFZL7erW.K2Mj2u2GMlP2tJyJlMaUndPoIKmtVd6Y6CM7a', 'hdmodel.jpg', 'verified', 'enabled', ''),
(2, 'user', 'Michael', 'Akpambo', 'Scofield', 'michaelscofield01@gmail.com', '081000', 'Male', 'PAS/ENG/19/007', '$2y$10$.1DgdkISG/CO/KQdLAOPQ.7jD.j1.LU7bd0v17Ztw5sW4cqK2hYp2', 'hddog.jpg', 'verified', 'disabled', ''),
(3, 'user', 'Tom', 'Ayimbo', 'Cruise', 'tomcruiser01@gmail.com', '09019803560', 'Male', 'PAS/CHM/19/007', '$2y$10$toRFP4WLwdX8vX6.dWO7kuWJXbkpgLfkKwn9qHuuNoJ95tXewwL.K', 'hdtomcruise.jpg', 'verified', 'enabled', ''),
(8, 'user', 'Samson', 'Gyelpanyi', 'David', 'samsongyelpanyi@gmail.com', '0900', 'Male', 'PAS/CSC/19/031', '$2y$10$G9R7HDMqCkad.3jIHJZivulqULph1n5N0JICpeEiy8IyRnrz9RsjS', '0c52bc1c76e1670b80e676e5b89ad3d7.jpg', 'verified', 'enabled', '0c52bc1c76e1670b80e676e5b89ad3d7.jpg'),
(14, 'user', 'Edward', '', 'Emmanuel', 'edemmanuuel245@gmail.com', '09142659673', 'Male', 'PAS/CSC/19/015', '$2y$10$MXfoft4foZDUQm42jZxlHO3kaSWO/dWbPN5MBzJUhm02lsR9ofy.u', NULL, 'unverified', 'enabled', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `item_categories`
--
ALTER TABLE `item_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `lost_items`
--
ALTER TABLE `lost_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `private_messages`
--
ALTER TABLE `private_messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_categories`
--
ALTER TABLE `item_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `lost_items`
--
ALTER TABLE `lost_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `private_messages`
--
ALTER TABLE `private_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `lost_items`
--
ALTER TABLE `lost_items`
  ADD CONSTRAINT `lost_items_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `lost_items_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `item_categories` (`category_id`);

--
-- Constraints for table `private_messages`
--
ALTER TABLE `private_messages`
  ADD CONSTRAINT `private_messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `private_messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`item_id`) REFERENCES `lost_items` (`item_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
