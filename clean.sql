-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2021 at 03:28 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `y2s3w_hw2`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` varchar(255) CHARACTER SET utf8 NOT NULL,
  `comment_post_id` varchar(255) CHARACTER SET utf8 NOT NULL,
  `comment_user_id` varchar(255) CHARACTER SET utf8 NOT NULL,
  `comment_parent_id` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `comment_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `c_likes`
--

CREATE TABLE `c_likes` (
  `clike_comment_id` varchar(255) CHARACTER SET utf8 NOT NULL,
  `clike_user_id` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` varchar(255) CHARACTER SET utf8 NOT NULL,
  `post_author_id` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `post_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_img` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `post_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `p_likes`
--

CREATE TABLE `p_likes` (
  `plike_post_id` varchar(255) CHARACTER SET utf8 NOT NULL,
  `plike_user_id` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `p_tags`
--

CREATE TABLE `p_tags` (
  `ptag_post_id` varchar(255) CHARACTER SET utf8 NOT NULL,
  `ptag_content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(255) CHARACTER SET utf8 NOT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `user_email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `user_quote` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_img` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `user_role` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `u_roles`
--

CREATE TABLE `u_roles` (
  `role_ID` tinyint(1) NOT NULL,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `u_roles`
--

INSERT INTO `u_roles` (`role_ID`, `role_name`) VALUES
(1, 'admin'),
(2, 'basic user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `FK_comment_parent_id` (`comment_parent_id`),
  ADD KEY `FK_comment_post_id` (`comment_post_id`),
  ADD KEY `FK_comment_user_id` (`comment_user_id`);

--
-- Indexes for table `c_likes`
--
ALTER TABLE `c_likes`
  ADD PRIMARY KEY (`clike_comment_id`,`clike_user_id`),
  ADD KEY `FK_CLIKE_USER_ID` (`clike_user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `FK_post_author_ID` (`post_author_id`);

--
-- Indexes for table `p_likes`
--
ALTER TABLE `p_likes`
  ADD PRIMARY KEY (`plike_post_id`,`plike_user_id`),
  ADD KEY `FK_PLIKE_USER_ID` (`plike_user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`),
  ADD UNIQUE KEY `user_email_2` (`user_email`),
  ADD KEY `FK_user_role` (`user_role`);

--
-- Indexes for table `u_roles`
--
ALTER TABLE `u_roles`
  ADD PRIMARY KEY (`role_ID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `FK_comment_parent_id` FOREIGN KEY (`comment_parent_ID`) REFERENCES `comments` (`comment_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_comment_post_id` FOREIGN KEY (`comment_post_ID`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_comment_user_id` FOREIGN KEY (`comment_user_ID`) REFERENCES `users` (`user_ID`) ON DELETE CASCADE;

--
-- Constraints for table `c_likes`
--
ALTER TABLE `c_likes`
  ADD CONSTRAINT `FK_CLIKE_COMMENT_ID` FOREIGN KEY (`clike_comment_id`) REFERENCES `comments` (`comment_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_CLIKE_USER_ID` FOREIGN KEY (`clike_user_id`) REFERENCES `users` (`user_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `FK_post_author_ID` FOREIGN KEY (`post_author_id`) REFERENCES `users` (`user_ID`) ON DELETE SET NULL;

--
-- Constraints for table `p_likes`
--
ALTER TABLE `p_likes`
  ADD CONSTRAINT `FK_PLIKE_POST_ID` FOREIGN KEY (`plike_post_ID`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_PLIKE_USER_ID` FOREIGN KEY (`plike_user_ID`) REFERENCES `users` (`user_ID`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_user_role` FOREIGN KEY (`user_role`) REFERENCES `u_roles` (`role_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
