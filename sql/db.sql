-- phpMyAdmin SQL Dump
-- version 4.4.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 19, 2016 at 12:32 PM
-- Server version: 5.1.73
-- PHP Version: 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `europa-re.com`
--
DROP DATABASE IF EXISTS `europa-re.com`;
CREATE DATABASE IF NOT EXISTS `europa-re.com` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `europa-re.com`;

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('PUBLISHED','DRAFT') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'PUBLISHED',
  `parent` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `featured_photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `user_id`, `type`, `status`, `parent`, `date`, `title`, `content`, `featured_photo`, `slug`, `created_at`, `updated_at`) VALUES
(1, 1, 'post', 'PUBLISHED', NULL, NULL, 'Arber Mustafa Slug', NULL, NULL, 'arber-mustafa-slug', '2016-08-19 13:09:29', '2016-08-12 00:00:00'),
(2, 1, 'menu', 'PUBLISHED', NULL, NULL, 'main-menu', '[{"title":"Menu 1", "url":"slug-url"}, {"title":"Menu 2", "url":"slug-url", "children":[{"title":"Menu 2.1", "url":"slug-url", "children":[{"title":"Menu 2.1.", "url":"some-random-title-to-generate-here"}]}]}]', NULL, NULL, NULL, '2016-08-16 13:39:24'),
(8, 1, 'page', 'PUBLISHED', NULL, NULL, 'Some random title to generate here', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eu enim eget diam maximus interdum. Nullam at nisl finibus, faucibus lacus vel, varius magna. Quisque pharetra rhoncus tellus a dictum. Nam consectetur lorem nunc, hendrerit fermentum urna scelerisque sit amet. Morbi ante erat, fringilla vel tempor vel, lacinia sit amet nisl. Ut fringilla interdum velit id dapibus. Vestibulum felis ante, suscipit eget nisi vel, gravida elementum massa. Proin ligula lectus, dictum eu elit sit amet, mollis rutrum odio. Sed faucibus, nulla nec iaculis cursus, mauris magna tincidunt sapien, a venenatis augue nulla quis nisi. Quisque vel leo in eros mattis malesuada.\r\n\r\nInteger in leo odio. Nulla facilisi. Nullam fringilla, velit nec finibus consequat, metus tellus mollis massa, id congue est nisi eu enim. Morbi sapien ipsum, consequat sit amet mauris sit amet, porta tincidunt sem. Aliquam erat volutpat. Donec interdum nunc augue, eu luctus sem dapibus id. Aliquam erat volutpat. Etiam nec metus vitae elit pretium ultricies sed ut augue. Vivamus euismod leo sit amet eros efficitur lobortis.\r\n\r\nNulla tempus, dui et mollis euismod, nunc ex fringilla dolor, sed fringilla leo ante at urna. Proin nec luctus odio. Ut id volutpat augue. Nam sit amet sem eget leo porta egestas nec ut mauris. Integer dapibus efficitur scelerisque. Ut ipsum felis, tempus in rutrum sit amet, aliquet facilisis nisl. Duis pharetra urna ut urna viverra luctus. Proin condimentum luctus nulla, in dignissim libero gravida quis. Suspendisse condimentum condimentum orci, sed tempus libero fermentum vitae. Praesent vel scelerisque erat, sit amet volutpat enim. Maecenas lorem odio, condimentum sed urna et, laoreet tincidunt ex. Suspendisse potenti. In pretium enim nibh, sed tempor libero posuere porttitor.', NULL, 'some-random-title-to-generate-here', '2016-08-15 15:50:53', '2016-08-16 10:00:11'),
(10, 1, 'page', 'PUBLISHED', NULL, NULL, 'Some random title to generate', NULL, NULL, 'some-random-title-to-generate-1', '2016-08-15 15:54:32', '2016-08-15 15:54:32'),
(12, 1, 'page', 'PUBLISHED', NULL, NULL, 'Some random title to generate', NULL, NULL, 'some-random-title-to-generate-2', '2016-08-15 15:54:46', '2016-08-15 15:54:46'),
(13, 1, 'page', 'PUBLISHED', NULL, NULL, 'Some random title to generate', NULL, NULL, 'some-random-title-to-generate-3', '2016-08-15 15:54:47', '2016-08-15 15:54:47'),
(14, 1, 'page', 'PUBLISHED', NULL, NULL, 'Some random title to generate', NULL, NULL, 'some-random-title-to-generate-4', '2016-08-15 15:54:47', '2016-08-15 15:54:47'),
(15, 1, 'page', 'PUBLISHED', NULL, NULL, 'Some random title to generate', NULL, NULL, 'some-random-title-to-generate-5', '2016-08-15 15:54:47', '2016-08-15 15:54:47'),
(16, 1, 'page', 'PUBLISHED', NULL, NULL, 'Some random title to generate', NULL, NULL, 'some-random-title-to-generate-6', '2016-08-15 15:54:48', '2016-08-15 15:54:48'),
(17, 1, 'page', 'PUBLISHED', NULL, NULL, 'Some random title to generate', NULL, NULL, 'some-random-title-to-generate-7', '2016-08-15 15:54:48', '2016-08-15 15:54:48'),
(18, 1, 'page', 'PUBLISHED', NULL, NULL, 'Some random title to generate', NULL, NULL, 'some-random-title-to-generate-8', '2016-08-15 15:55:03', '2016-08-15 15:55:03'),
(19, 1, 'page', 'PUBLISHED', NULL, NULL, 'Some random title to generate', NULL, NULL, 'some-random-title-to-generate-9', '2016-08-15 15:55:03', '2016-08-15 15:55:03'),
(26, 1, 'page', 'PUBLISHED', NULL, NULL, 'Some random title to generate', NULL, NULL, 'some-random-title-to-generate-10', '2016-08-15 16:02:21', '2016-08-15 16:02:21'),
(31, 1, 'page', 'PUBLISHED', NULL, NULL, 'Some random title to generate', NULL, NULL, 'some-random-title-to-generate-11', '2016-08-16 09:27:43', '2016-08-16 09:27:43'),
(33, 1, 'menu', 'PUBLISHED', NULL, NULL, 'header-menu', '[{"title":"Home link", "url":"slug-url"}, {"title":"Home link 2", "url":"slug-url-2"}, {"title":"Home link 3", "url":"slug-url-3"}]', NULL, NULL, '2016-08-16 13:38:34', '2016-08-16 13:39:01'),
(35, 1, 'menu', 'PUBLISHED', NULL, NULL, 'footer-menu', '[{"title":"Home link", "url":"slug-url"}]', NULL, NULL, '2016-08-16 13:43:36', '2016-08-16 14:27:41'),
(37, 1, 'category', 'PUBLISHED', 36, NULL, 'News from world', NULL, NULL, 'news-from-world', NULL, NULL),
(39, 1, 'category', 'PUBLISHED', NULL, NULL, 'New cateegory title 2', NULL, NULL, 'new-cateegory-title-2', '2016-08-16 15:00:49', '2016-08-16 15:28:17');

-- --------------------------------------------------------

--
-- Table structure for table `post_category`
--

DROP TABLE IF EXISTS `post_category`;
CREATE TABLE IF NOT EXISTS `post_category` (
  `content_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

DROP TABLE IF EXISTS `setting`;
CREATE TABLE IF NOT EXISTS `setting` (
  `id` int(11) NOT NULL,
  `key_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `key_value` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `key_name`, `key_value`) VALUES
(13, 'fb', 'https://facebook.com/arber.mustafa'),
(14, 'in', '');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `firstname` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `role` enum('ADMIN','AUTHOR') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ADMIN',
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ACTIVE',
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `email`, `password`, `role`, `status`, `last_login`) VALUES
(1, 'Arberarber', 'Mustafa', 'amustafa@medialb.net', '2a02ee3296b3e458f664681556112ba7', 'ADMIN', 'ACTIVE', '2016-08-18 10:53:31'),
(2, 'Loren', 'Haxhi', 'loren@medialb.net', 'asdasdadasd', 'AUTHOR', 'ACTIVE', NULL),
(3, 'Loren2', 'Haxhi', 'loren2@medialb.net', 'asdasdadasd', 'AUTHOR', 'ACTIVE', NULL),
(4, 'Arber2', 'Mustafa', 'amustafa2@medialb.net', 'asdasdas', 'ADMIN', 'ACTIVE', NULL),
(5, 'Loren3', 'Haxhi', 'loren3@medialb.net', 'asdasdadasd', 'AUTHOR', 'ACTIVE', NULL),
(6, 'Arber3', 'Mustafa', 'amustafa3@medialb.net', 'asdasdas', 'ADMIN', 'ACTIVE', NULL),
(7, 'Loren4', 'Haxhi', 'loren4@medialb.net', 'asdasdadasd', 'AUTHOR', 'ACTIVE', NULL),
(8, 'Arber4', 'Mustafa', 'amustafa4@medialb.net', 'asdasdas', 'ADMIN', 'ACTIVE', NULL),
(9, 'Loren5', 'Haxhi', 'loren5@medialb.net', 'asdasdadasd', 'AUTHOR', 'ACTIVE', NULL),
(10, 'Arber5', 'Mustafa', 'amustafa5@medialb.net', 'asdasdas', 'ADMIN', 'ACTIVE', NULL),
(11, 'Loren6', 'Haxhi', 'loren6@medialb.net', 'asdasdadasd', 'AUTHOR', 'ACTIVE', NULL),
(12, 'Arberarber', 'Mustafa', 'amustafa20@medialb.net', '5e41fad74b331deb46ffad671a096429', 'ADMIN', 'ACTIVE', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `title` (`title`);

--
-- Indexes for table `post_category`
--
ALTER TABLE `post_category`
  ADD KEY `content_id` (`content_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_name` (`key_name`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `content`
--
ALTER TABLE `content`
  ADD CONSTRAINT `content_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `post_category`
--
ALTER TABLE `post_category`
  ADD CONSTRAINT `pc_category_id` FOREIGN KEY (`category_id`) REFERENCES `content` (`id`),
  ADD CONSTRAINT `pc_content_id` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`);

