-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2024 at 01:29 PM
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
-- Database: `management`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `date` date NOT NULL,
  `rmFjMed2` int(1) NOT NULL DEFAULT 0,
  `4lc4CWQ9` int(1) DEFAULT 0,
  `rAfSceHn` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`date`, `rmFjMed2`, `4lc4CWQ9`, `rAfSceHn`) VALUES
('2024-04-01', 0, 1, 0),
('2024-04-02', 0, 1, 1),
('2024-04-03', 0, 3, 1),
('2024-04-04', 0, 1, 3),
('2024-04-05', 0, 1, 1),
('2024-04-06', 0, 1, 3),
('2024-04-07', 4, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(8) NOT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `pass` text NOT NULL,
  `img` text NOT NULL,
  `status` varchar(7) NOT NULL DEFAULT 'student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `pass`, `img`, `status`) VALUES
('1rhqYYnp', 'admin', 'quirkyshaw1@typingsquirrel.com', '$2y$10$oy/N8Zj/vRYSKAe2HmHKjuh6XRMuJMkfqev2m6jAvW6cUbSu16aDO', 'none', 'admin'),
('4lc4CWQ9', 'John Doe', 'romanticfranklin@freethecookies.com', '$2y$10$.MUWpkeOXj.aOlUr75qR3.mwINjCPKJ76lDgTQLdpdR.Z9nl5kfwi', '660a6b514e3756.03854614.jpg', 'student'),
('rAfSceHn', 'Micheal Clover', 'optimisticdijkstra9@tomorjerry.com', '$2y$10$cbzONnFD08x/MRMNuSu0JeUUR7moVxiaK6YnLQ2TJnAVIOi7OBh42', 'none', 'student'),
('rmFjMed2', 'Harry Van Dusen', 'recursingrosalind9@freethecookies.com', '$2y$10$LQv1TAUEnLe8c91/ULlLAOihhiQ4d/Y0eT5DbrWJGLdvqHyzQeXF.', '661281c3922d65.65909681.png', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`date`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
