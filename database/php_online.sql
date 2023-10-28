-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2023 at 03:28 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php_online`
--

-- --------------------------------------------------------

--
-- Table structure for table `login_token`
--

CREATE TABLE IF NOT EXISTS `login_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `token` varchar(50) DEFAULT NULL,
  `createAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `login_token`
--

INSERT INTO `login_token` (`id`, `userId`, `token`, `createAt`) VALUES
(19, 13, '7fc99f33bea5eb5570c520d5dab97198027ec4b8', '2023-10-28 20:14:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `forgotToken` varchar(50) DEFAULT NULL,
  `activeToken` varchar(50) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0,
  `lastActivity` datetime DEFAULT NULL,
  `createAt` datetime DEFAULT NULL,
  `updateAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `fullname`, `phone`, `password`, `forgotToken`, `activeToken`, `status`, `lastActivity`, `createAt`, `updateAt`) VALUES
(2, 'nguyenvana@gmail.com', 'Nguyễn Văn A', '0342449594', '$2y$10$DV1RBxCo879C8VPymHGNyuTGHEoyS6fXir1X5vlSLyA3xqTpvoPXK', NULL, NULL, 1, NULL, '2023-10-24 14:51:47', '2023-10-24 14:51:47'),
(3, 'tranthibinhminh@gmail.com', 'Trần Thị Bình Minh', '0775083586', '$2y$10$DV1RBxCo879C8VPymHGNyuTGHEoyS6fXir1X5vlSLyA3xqTpvoPXK', NULL, NULL, 1, NULL, '2023-10-24 14:53:03', '2023-10-24 14:53:03'),
(4, 'ngovinhthong@gmail.com', 'Ngô Vĩnh Thông', '0998856321', '$2y$10$DV1RBxCo879C8VPymHGNyuTGHEoyS6fXir1X5vlSLyA3xqTpvoPXK', NULL, NULL, 1, NULL, '2023-10-24 14:54:03', '2023-10-24 14:53:03'),
(5, 'dinhvanhen@gmail.com', 'Đinh Văn Hên', '0353218146', '$2y$10$DV1RBxCo879C8VPymHGNyuTGHEoyS6fXir1X5vlSLyA3xqTpvoPXK', NULL, NULL, 1, NULL, '2023-10-24 14:55:03', '2023-10-24 14:53:03'),
(6, 'dohoangnam@gmail.com', 'Đỗ Hoàng Nam', '0909995123', '$2y$10$DV1RBxCo879C8VPymHGNyuTGHEoyS6fXir1X5vlSLyA3xqTpvoPXK', NULL, NULL, 1, NULL, '2023-10-24 14:56:03', '2023-10-24 14:53:03'),
(7, 'laivannho@gmail.com', 'Lại Văn Nho', '0919143521', '$2y$10$DV1RBxCo879C8VPymHGNyuTGHEoyS6fXir1X5vlSLyA3xqTpvoPXK', NULL, NULL, 0, NULL, '2023-10-24 14:57:03', '2023-10-27 21:02:41'),
(8, 'leviethoangnguyen@gmail.com', 'Lê Viết Hoàng Nguyên', '0793442309', '$2y$10$DV1RBxCo879C8VPymHGNyuTGHEoyS6fXir1X5vlSLyA3xqTpvoPXK', NULL, NULL, 0, NULL, '2023-10-24 14:58:03', '2023-10-24 14:53:03'),
(9, 'nguyenducchuan@gmail.com', 'Nguyễn Đức Chuẩn', '0707353212', '$2y$10$DV1RBxCo879C8VPymHGNyuTGHEoyS6fXir1X5vlSLyA3xqTpvoPXK', NULL, NULL, 0, NULL, '2023-10-24 14:59:03', '2023-10-24 14:53:03'),
(10, 'nguyenbinhkhiem@gmail.com', 'Nguyễn Bỉnh Khiêm', '0337899870', '$2y$10$DV1RBxCo879C8VPymHGNyuTGHEoyS6fXir1X5vlSLyA3xqTpvoPXK', NULL, NULL, 1, NULL, '2023-10-24 15:53:03', '2023-10-27 21:07:52'),
(11, 'nguyenminhtot@gmail.com', 'Nguyễn Minh Tốt', '0377483212', '$2y$10$FhsYi/uTTWqHJYFJ5gk00ujR7tBrCQDZnt9iOVhkKXfFksROxhPPK', NULL, NULL, 1, NULL, '2023-10-27 20:07:29', NULL),
(12, 'levanluyen@gmail.com', 'Lê Văn Luyện', '0996537728', '$2y$10$pCtddMcp0wrO9TN9ysR8bO/Fe3FXPAim0btioWXlPkt2kyGSnwQjO', NULL, NULL, 1, NULL, '2023-10-27 20:09:15', '2023-10-27 21:12:18'),
(13, 'laiminhkiet07052002@gmail.com', 'Lại Minh Kiệt', '0376707091', '$2y$10$yv9kUObtZUV72ESdWtigT.GJPRAcZjuP8q6j1QsNmH4dEqMw70yRq', NULL, NULL, 1, '2023-10-28 20:27:20', '2023-10-28 19:15:33', NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `login_token`
--
ALTER TABLE `login_token`
  ADD CONSTRAINT `login_token_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
