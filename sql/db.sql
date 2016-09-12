-- phpMyAdmin SQL Dump
-- version 4.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 12, 2016 at 03:13 PM
-- Server version: 5.1.73
-- PHP Version: 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `europa-re.com`
--
DROP DATABASE IF EXISTS `europa-re.com`;
CREATE DATABASE IF NOT EXISTS `europa-re.com` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
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
  `template` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `featured_photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `user_id`, `type`, `status`, `template`, `parent`, `date`, `title`, `content`, `featured_photo`, `slug`, `created_at`, `updated_at`) VALUES
(2, 1, 'menu', 'PUBLISHED', '', NULL, NULL, 'main-menu', '[{"title":"Home","url":"/"},{"id":46}]', NULL, NULL, NULL, '2016-09-12 14:35:32'),
(33, 1, 'menu', 'PUBLISHED', '', NULL, NULL, 'header-menu', '[{"title":"Home","url":"/"},{"id":65},{"id":66}]', NULL, NULL, '2016-08-16 13:38:34', '2016-09-12 14:22:55'),
(35, 1, 'menu', 'PUBLISHED', '', NULL, NULL, 'footer-menu', '[{"id":63},{"id":64},{"id":65}]', NULL, NULL, '2016-08-16 13:43:36', '2016-09-12 13:23:16'),
(44, 1, 'category', 'PUBLISHED', '', NULL, NULL, 'Press releases', NULL, NULL, 'press-releases', '2016-08-28 12:55:38', '2016-09-12 14:33:26'),
(45, 1, 'category', 'PUBLISHED', '', NULL, NULL, 'Europa Re in the news', NULL, NULL, 'europa-re-in-the-news', '2016-08-28 15:04:13', '2016-09-12 14:33:44'),
(46, 1, 'category', 'PUBLISHED', '', NULL, NULL, 'News archive', NULL, NULL, 'news-archive', '2016-08-28 15:06:59', '2016-09-12 14:34:02'),
(58, 1, 'post', 'PUBLISHED', '', NULL, '2016-09-10 00:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla libero nisl, porta non neque sit amet, rhoncus tempus quam. Morbi semper tellus eu magna mattis venenatis.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ut viverra turpis, eget molestie diam. Sed vel arcu metus. Sed risus dui, facilisis rutrum est non, varius condimentum urna. Mauris ut justo eros. Pellentesque tempor elementum viverra. Etiam rutrum, turpis ac venenatis eleifend, nibh quam varius elit, non lacinia felis quam non dui. Nullam tristique mollis augue, vel scelerisque velit viverra vitae. Donec vel ornare ipsum. Etiam semper massa at convallis pellentesque. Sed hendrerit, enim nec bibendum feugiat, metus mi fermentum tortor, quis finibus diam erat eu tortor. Sed dui erat, tempus et lacus ut, sagittis sagittis mi. Vestibulum tempus lectus ipsum, et lobortis nulla condimentum in. Nullam in massa sed massa mattis dapibus eu nec felis. Nunc id lacus molestie, blandit sem suscipit, tincidunt neque.<br />\r\n<br />\r\nDonec commodo facilisis congue. Sed congue faucibus justo eget rhoncus. Curabitur et nibh id dui sodales sagittis. Vivamus et aliquet leo, non elementum dui. Aenean eu tristique orci. Nulla dignissim tempor convallis. Fusce tristique nibh in nibh sodales pulvinar.<br />\r\n<br />\r\nCras et massa fermentum, consequat tellus eget, eleifend neque. Nulla nec volutpat tellus, id blandit ex. Quisque iaculis tincidunt scelerisque. Etiam a laoreet dolor, vitae aliquet arcu. Maecenas in ante eget eros facilisis vehicula. Nulla tincidunt at lorem vitae consequat. Aliquam nec ornare tortor, sit amet mollis ex. Donec sollicitudin lectus vitae magna porttitor mattis. In dictum lectus ac tincidunt ultrices.<br />\r\n<br />\r\nQuisque erat augue, blandit in quam at, vehicula convallis quam. Maecenas justo odio, commodo sit amet accumsan a, pulvinar ut metus. Donec at bibendum nunc, a semper orci. In vitae tristique tortor, id laoreet ex. Nam et eros congue, scelerisque elit vitae, gravida urna. Suspendisse dapibus suscipit hendrerit. Aenean blandit felis eget diam gravida commodo. Integer varius enim fermentum nisl aliquet, et elementum nisi rhoncus.<br />\r\n<br />\r\nAliquam pellentesque diam in mollis tempus. Etiam tincidunt ligula ac nibh semper auctor at a nunc. Donec sit amet mi euismod, dignissim ipsum quis, vulputate urna. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla vitae dui quis felis blandit semper nec in risus. Morbi tempor fermentum lectus et semper. Quisque fringilla velit ipsum, ac luctus arcu placerat ac. Proin in velit blandit, bibendum lorem id, blandit neque. Morbi tincidunt felis ut massa vehicula lacinia. Vivamus id arcu eu tellus elementum maximus.<br />\r\n<br />\r\nGenerated 5 paragraphs, 377 words, 2504 bytes of Lorem Ipsum<br />\r\n<br />\r\nDownload the file <a href="/uploads/Gjumi-Kapitulli-5-1.pdf">here</a>', NULL, 'lorem-ipsum-dolor-sit-amet-consectetur-adipiscing-elit-nulla-libero-nisl-porta-non-neque-sit-amet-rhoncus-tempus-quam-morbi-semper-tellus-eu-magna-mattis-venenatis', '2016-09-12 11:29:39', '2016-09-12 14:35:19'),
(59, 1, 'post', 'PUBLISHED', '', NULL, '2016-09-12 00:00:00', 'Today news', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ut viverra turpis, eget molestie diam. Sed vel arcu metus. Sed risus dui, facilisis rutrum est non, varius condimentum urna. Mauris ut justo eros. Pellentesque tempor elementum viverra. Etiam rutrum, turpis ac venenatis eleifend, nibh quam varius elit, non lacinia felis quam non dui. Nullam tristique mollis augue, vel scelerisque velit viverra vitae. Donec vel ornare ipsum. Etiam semper massa at convallis pellentesque. Sed hendrerit, enim nec bibendum feugiat, metus mi fermentum tortor, quis finibus diam erat eu tortor. Sed dui erat, tempus et lacus ut, sagittis sagittis mi. Vestibulum tempus lectus ipsum, et lobortis nulla condimentum in. Nullam in massa sed massa mattis dapibus eu nec felis. Nunc id lacus molestie, blandit sem suscipit, tincidunt neque.<br />\r\n<br />\r\nDonec commodo facilisis congue. Sed congue faucibus justo eget rhoncus. Curabitur et nibh id dui sodales sagittis. Vivamus et aliquet leo, non elementum dui. Aenean eu tristique orci. Nulla dignissim tempor convallis. Fusce tristique nibh in nibh sodales pulvinar.<br />\r\n<br />\r\nCras et massa fermentum, consequat tellus eget, eleifend neque. Nulla nec volutpat tellus, id blandit ex. Quisque iaculis tincidunt scelerisque. Etiam a laoreet dolor, vitae aliquet arcu. Maecenas in ante eget eros facilisis vehicula. Nulla tincidunt at lorem vitae consequat. Aliquam nec ornare tortor, sit amet mollis ex. Donec sollicitudin lectus vitae magna porttitor mattis. In dictum lectus ac tincidunt ultrices.<br />\r\n<br />\r\nQuisque erat augue, blandit in quam at, vehicula convallis quam. Maecenas justo odio, commodo sit amet accumsan a, pulvinar ut metus. Donec at bibendum nunc, a semper orci. In vitae tristique tortor, id laoreet ex. Nam et eros congue, scelerisque elit vitae, gravida urna. Suspendisse dapibus suscipit hendrerit. Aenean blandit felis eget diam gravida commodo. Integer varius enim fermentum nisl aliquet, et elementum nisi rhoncus.<br />\r\n<br />\r\nAliquam pellentesque diam in mollis tempus. Etiam tincidunt ligula ac nibh semper auctor at a nunc. Donec sit amet mi euismod, dignissim ipsum quis, vulputate urna. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla vitae dui quis felis blandit semper nec in risus. Morbi tempor fermentum lectus et semper. Quisque fringilla velit ipsum, ac luctus arcu placerat ac. Proin in velit blandit, bibendum lorem id, blandit neque. Morbi tincidunt felis ut massa vehicula lacinia. Vivamus id arcu eu tellus elementum maximus.<br />\r\n<br />\r\nGenerated 5 paragraphs, 377 words, 2504 bytes of Lorem Ipsum', 'img-20160912113955-57d677eb863b5.jpg', 'today-news', '2016-09-12 11:38:17', '2016-09-12 11:39:56'),
(61, 1, 'slide', 'PUBLISHED', NULL, NULL, NULL, 'Slide', '', 'img-20160912131605-57d68e7529b14.jpg', NULL, '2016-09-12 13:15:32', '2016-09-12 13:16:05'),
(62, 1, 'slide', 'PUBLISHED', NULL, NULL, NULL, 'Slide 2', '', 'img-20160912131628-57d68e8c79796.jpg', NULL, '2016-09-12 13:16:28', '2016-09-12 13:16:28'),
(63, 1, 'page', 'PUBLISHED', 'default', NULL, NULL, 'Privacy Policy', '', NULL, 'privacy-policy', '2016-09-12 13:18:03', '2016-09-12 13:18:03'),
(64, 1, 'page', 'PUBLISHED', 'default', NULL, NULL, 'Legal Notice', '', NULL, 'legal-notice', '2016-09-12 13:18:38', '2016-09-12 13:18:38'),
(65, 1, 'page', 'PUBLISHED', 'sitemap', NULL, NULL, 'Sitemap', '', NULL, 'sitemap', '2016-09-12 13:19:09', '2016-09-12 13:19:09'),
(66, 1, 'page', 'PUBLISHED', 'default', NULL, NULL, 'Imprint', '', NULL, 'imprint', '2016-09-12 13:41:38', '2016-09-12 13:41:38'),
(67, 1, 'slide', 'PUBLISHED', NULL, NULL, NULL, 'Slide 3', 'description', 'img-20160912142450-57d69e9247b8f.png', NULL, '2016-09-12 14:24:50', '2016-09-12 14:24:50'),
(68, 1, 'post', 'PUBLISHED', NULL, NULL, '2016-09-12 00:00:00', 'Some post title', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla libero nisl, porta non neque sit amet, rhoncus tempus quam. Morbi semper tellus eu magna mattis venenatis. Aenean maximus diam sit amet imperdiet feugiat. Suspendisse auctor pharetra augue vulputate eleifend. Ut vehicula sollicitudin lobortis. Vivamus eget vestibulum diam, eget scelerisque augue. Sed dictum eros euismod sem condimentum vehicula. Ut condimentum nisl neque, quis pharetra turpis eleifend ut. Ut erat ipsum, placerat vel tincidunt sed, tristique vitae urna. Interdum et malesuada fames ac ante ipsum primis in faucibus. Praesent auctor metus nec nisl tincidunt, id ullamcorper purus accumsan. Sed a mauris viverra, consequat nisi quis, finibus metus.<br />\r\n<br />\r\nIn varius tortor sed scelerisque semper. Ut at consectetur arcu. Quisque mattis diam sollicitudin vulputate pharetra. Aliquam eget elit blandit justo hendrerit rhoncus quis id tellus. Suspendisse id purus enim. In facilisis, elit nec lacinia consequat, mi orci dictum velit, imperdiet malesuada risus arcu sed tortor. Maecenas vehicula volutpat massa non efficitur. Donec hendrerit ullamcorper est. Cras suscipit nisi vel mi efficitur, vitae consequat erat pulvinar. Quisque a libero auctor, posuere dui vel, ornare tortor. Curabitur eget sem sed enim congue venenatis. Vivamus accumsan vitae nisl ac dignissim.<br />\r\n<br />\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur luctus sodales consequat. Nulla eget turpis eu turpis laoreet faucibus. Nulla facilisi. Aenean non risus erat. Curabitur ullamcorper enim ante, eget euismod tellus semper eu. Proin sed aliquam tellus, et condimentum sem. Nullam ut nibh turpis. Morbi placerat viverra diam. Donec euismod, ante sit amet scelerisque porta, arcu arcu tempus felis, id viverra dui elit tincidunt augue. Quisque vel tortor aliquam, pulvinar enim vel, dictum diam. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec at malesuada lorem.<br />\r\n<br />\r\nNunc sodales justo orci, sed euismod quam laoreet eget. Integer eu tincidunt mauris. Aliquam ut urna nisi. Quisque dapibus quam ut pellentesque suscipit. Integer vitae libero non ipsum rhoncus aliquet. In sed nibh dui. Aliquam ut sodales ante. Proin scelerisque eros vel odio lacinia, eget hendrerit ipsum consequat. In at lectus tincidunt, consequat metus sit amet, lacinia arcu. Integer ut orci et augue suscipit finibus id nec sapien. Duis eu lacus vel erat maximus faucibus eget nec diam. Cras sit amet eleifend purus. Aliquam erat volutpat. Vestibulum purus dui, pretium non imperdiet eu, ultrices ut nibh. Morbi nec diam non tellus vulputate gravida sit amet vel eros.<br />\r\n<br />\r\nFusce eros nisi, sodales nec ullamcorper suscipit, vestibulum in purus. In dapibus odio vitae dui faucibus ullamcorper. Ut egestas aliquet consequat. Praesent scelerisque dolor id scelerisque bibendum. Sed suscipit lorem quam, a luctus risus porttitor facilisis. Sed sem est, finibus eu dignissim sed, consequat at quam. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vivamus iaculis massa sit amet nisi vulputate, ac pretium sapien rhoncus. Quisque quam dui, gravida et scelerisque pellentesque, tincidunt vitae lacus. Mauris id arcu blandit, venenatis dui a, egestas lorem. Sed erat urna, vehicula in vestibulum in, gravida non arcu. Fusce ac turpis eget nunc vulputate consequat. Curabitur risus ex, facilisis at pharetra at, lobortis vitae lectus. In pharetra sollicitudin sapien, auctor lobortis magna tristique at. Integer convallis vestibulum libero quis imperdiet. In mauris lacus, pulvinar et pellentesque in, blandit sit amet erat.<br />\r\n<br />\r\nGenerated 5 paragraphs, 534 words, 3622 bytes of Lorem Ipsum<br />\r\n<br />\r\n<img alt="" src="/uploads/img-20160912113955-57d677eb863b5.jpg" style="width: 350px; height: 233px; float: left;" /><br />', NULL, 'some-post-title', '2016-09-12 14:26:16', '2016-09-12 14:26:16'),
(69, 1, 'category', 'PUBLISHED', NULL, NULL, NULL, 'Conferences and Events', NULL, NULL, 'conferences-and-events', '2016-09-12 14:34:19', '2016-09-12 14:34:19');

-- --------------------------------------------------------

--
-- Table structure for table `post_category`
--

DROP TABLE IF EXISTS `post_category`;
CREATE TABLE IF NOT EXISTS `post_category` (
  `content_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post_category`
--

INSERT INTO `post_category` (`content_id`, `category_id`) VALUES
(58, 46),
(59, 45),
(59, 46),
(68, 46);

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

DROP TABLE IF EXISTS `setting`;
CREATE TABLE IF NOT EXISTS `setting` (
`id` int(11) NOT NULL,
  `key_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `key_value` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`, `key_name`, `key_value`) VALUES
(13, 'fb', 'https://facebook.com/arber.mustafa'),
(14, 'in', 'https://facebook.com/arber.mustafas'),
(15, 'keywords', 'some keywords'),
(16, 'description', ''),
(17, 'analytics', '<script>\r\n        (function(i,s,o,g,r,a,m){i[''GoogleAnalyticsObject'']=r;i[r]=i[r]||function(){\r\n        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),\r\n        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)\r\n        })(window,document,''script'',''//www.google-analytics.com/analytics.js'',''ga'');\r\n\r\n        ga(''create'', ''UA-62525446-2'', ''auto'');\r\n        ga(''send'', ''pageview'');\r\n    </script>'),
(18, 'cfe', '');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `firstname`, `lastname`, `email`, `password`, `role`, `status`, `last_login`) VALUES
(1, 'Arber', 'Mustafa', 'arber9@gmail.com', '2a02ee3296b3e458f664681556112ba7', 'ADMIN', 'ACTIVE', '2016-09-12 15:10:40'),
(12, 'Donald', 'Papalilo', 'd.papalilo@gmail.com', '68ff389293f5a7435596471291b5dd6d', 'ADMIN', 'ACTIVE', '2016-09-12 14:36:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `content`
--
ALTER TABLE `content`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `slug` (`slug`), ADD KEY `user_id` (`user_id`), ADD KEY `title` (`title`);

--
-- Indexes for table `post_category`
--
ALTER TABLE `post_category`
 ADD KEY `content_id` (`content_id`), ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `key_name` (`key_name`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=70;
--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
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
