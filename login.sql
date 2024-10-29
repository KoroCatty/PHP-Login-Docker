-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 28, 2024 at 02:18 PM
-- Server version: 5.7.39
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `login`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `validation_key` varchar(255) NOT NULL,
  `registration_date` datetime NOT NULL,
  `is_active` int(11) NOT NULL DEFAULT '0',
  `user_email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `user_name`, `user_password`, `validation_key`, `registration_date`, `is_active`, `user_email`) VALUES
(1, 'ghdfghdfgh', 'property', 'aaa1A', '$2y$12$HJp/RDGGJmgTXYBXi9YY2u2aMtoKDn1x9EzFFg7AkhQBQzKx5w3Lq', 'aaa1A', '2024-10-27 13:28:53', 0, 'kz-code-web@email.com'),
(2, 'ghdfghdfgh', 'property', 'aaa1A', '$2y$12$5ClcWZ5eXyl7o9LTWNUHYO4W7Ht/ji7wO54ZBaHxLm9MJYbrwJmg6', 'aaa1A', '2024-10-27 13:35:15', 0, 'kz-code-web@email.com'),
(3, 'gsdfg', 'sdfgsdfgg', 'aaaA1', '$2y$12$44G3OCoHP1/./VZzvhHmjeBDS5yU.qfOJGKNgB4Au2wLQUjio.DUq', 'aaaA1', '2024-10-27 13:36:11', 0, 'kz-code-web@email.com'),
(4, 'gsdfg', 'sdfgsdfgg', 'aaaA1', '$2y$12$R1pghHC7U5rsqDEU6z97ZeLWIwPeVgwsaS5ev2LtNq272xKT5qZyi', 'aaaA1', '2024-10-27 13:37:32', 0, 'kz-code-web@email.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
