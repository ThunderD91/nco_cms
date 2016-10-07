-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Vært: 127.0.0.1
-- Genereringstid: 07. 10 2016 kl. 10:43:52
-- Serverversion: 5.6.16
-- PHP-version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hfp2_nco_cms`
--

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `event_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `event_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `event_description` text NOT NULL,
  `event_access_level_required` smallint(4) unsigned NOT NULL,
  `fk_user_id` mediumint(8) unsigned DEFAULT NULL,
  `fk_event_type_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`event_id`),
  KEY `fk_user_id` (`fk_user_id`),
  KEY `fk_event_type_id` (`fk_event_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=123 ;

--
-- Data dump for tabellen `events`
--

INSERT INTO `events` (`event_id`, `event_time`, `event_description`, `event_access_level_required`, `fk_user_id`, `fk_event_type_id`) VALUES
(99, '2016-10-06 11:40:04', 'af menu linket <a href="index.php?page=menu-link-edit&menu-id=1&id=2" data-page="menu-link-edit" data-params="menu-id=1&id=2">tesrere</a>', 100, 1, 1),
(100, '2016-10-06 11:43:42', 'af side menu linket <a href="index.php?page=menu-links-edit&menu-id=1&id=2" data-page="menu-links-edit" data-params="menu-id=1&id=2"></a>', 100, 1, 2),
(101, '2016-10-06 11:45:11', 'af side menu linket <a href="index.php?page=menu-links-edit&menu-id=1&id=2" data-page="menu-links-edit" data-params="menu-id=1&id=2">tesrere</a>', 100, 1, 2),
(102, '2016-10-06 11:45:23', 'af side menu linket <a href="index.php?page=menu-links-edit&menu-id=1&id=2" data-page="menu-links-edit" data-params="menu-id=1&id=2">tesrere</a>', 100, 1, 2),
(103, '2016-10-06 11:46:01', 'af side menu linket <a href="index.php?page=menu-links-edit&menu-id=1&id=2" data-page="menu-links-edit" data-params="menu-id=1&id=2">tesrere</a>', 100, 1, 2),
(104, '2016-10-06 11:46:13', 'af menu linket tesrere', 100, 1, 3),
(105, '2016-10-06 11:46:15', 'af menu linket ', 100, 1, 3),
(106, '2016-10-06 11:48:39', 'af post asd', 10, 1, 3),
(107, '2016-10-06 11:49:40', 'af menu linket <a href="index.php?page=menu-link-edit&menu-id=1&id=3" data-page="menu-link-edit" data-params="menu-id=1&id=3">tester</a>', 100, 1, 1),
(108, '2016-10-06 12:26:48', 'af menu linket asd', 100, 1, 3),
(109, '2016-10-06 12:26:50', 'af menu linket tester', 100, 1, 3),
(110, '2016-10-06 12:27:00', 'af menu linket <a href="index.php?page=menu-link-edit&menu-id=1&id=4" data-page="menu-link-edit" data-params="menu-id=1&id=4">tesasd</a>', 100, 1, 1),
(111, '2016-10-06 12:35:42', 'af menu linket <a href="index.php?page=menu-link-edit&menu-id=1&id=5" data-page="menu-link-edit" data-params="menu-id=1&id=5">asdds</a>', 100, 1, 1),
(112, '2016-10-06 12:35:48', 'af menu linket <a href="index.php?page=menu-link-edit&menu-id=1&id=6" data-page="menu-link-edit" data-params="menu-id=1&id=6">tersfd</a>', 100, 1, 1),
(113, '2016-10-06 12:35:57', 'af menu linket asdds', 100, 1, 3),
(114, '2016-10-06 12:37:09', 'af menu linket asdds', 100, 1, 3),
(115, '2016-10-06 12:37:11', 'af menu linket tesasd', 100, 1, 3),
(116, '2016-10-06 12:38:42', 'af side indholdet ', 100, 1, 3),
(117, '2016-10-06 12:39:13', 'af side indholdet asdds', 100, 1, 3),
(118, '2016-10-07 07:19:21', 'af post <a href="index.php?page=post-edit&id=2" data-page="post-edit" data-params="id=2">tester212</a>', 10, 1, 1),
(119, '2016-10-07 08:00:49', 'af <a href="index.php?page=comment-edit&post-id=1&id=4" data-page="comment-edit" data-params="post-id=1&id=4">Kommentar</a>', 10, 1, 1),
(120, '2016-10-07 08:00:56', 'af <a href="index.php?page=comment-edit&post-id=1&id=1" data-page="comment-edit" data-params="post-id=1&id=1">Kommentar</a>', 10, 1, 2),
(121, '2016-10-07 08:01:21', 'af <a href="index.php?page=comment-edit&post-id=1&id=1" data-page="comment-edit" data-params="post-id=1&id=1">Kommentar</a>', 10, 1, 2),
(122, '2016-10-07 08:17:52', 'Loggede in', 10, 2, 4);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `event_types`
--

CREATE TABLE IF NOT EXISTS `event_types` (
  `event_type_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `event_type_name` varchar(45) NOT NULL,
  `event_type_class` varchar(7) NOT NULL,
  PRIMARY KEY (`event_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Data dump for tabellen `event_types`
--

INSERT INTO `event_types` (`event_type_id`, `event_type_name`, `event_type_class`) VALUES
(1, 'CREATION', 'success'),
(2, 'UPDATE', 'warning'),
(3, 'DELETION', 'danger'),
(4, 'INFORMATION', 'info');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `menu_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(60) COLLATE utf8_danish_ci NOT NULL,
  `menu_description` varchar(180) COLLATE utf8_danish_ci NOT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci AUTO_INCREMENT=4 ;

--
-- Data dump for tabellen `menus`
--

INSERT INTO `menus` (`menu_id`, `menu_name`, `menu_description`) VALUES
(1, 'Main', 'Hovedmenu på hjemmesiden'),
(2, 'Footer', 'Menu i bunden af hjemmesiden'),
(3, 'Side', 'Side menu i siden');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `menu_links`
--

CREATE TABLE IF NOT EXISTS `menu_links` (
  `menu_link_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `menu_link_order` tinyint(3) unsigned NOT NULL,
  `menu_link_name` varchar(55) COLLATE utf8_danish_ci NOT NULL,
  `menu_link_type` tinyint(3) unsigned NOT NULL,
  `fk_page_id` mediumint(8) unsigned DEFAULT NULL,
  `fk_post_id` int(10) unsigned DEFAULT NULL,
  `fk_menu_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`menu_link_id`),
  KEY `fk_page_id` (`fk_page_id`,`fk_post_id`),
  KEY `fk_menu_id` (`fk_menu_id`),
  KEY `fk_post_id` (`fk_post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci AUTO_INCREMENT=7 ;

--
-- Data dump for tabellen `menu_links`
--

INSERT INTO `menu_links` (`menu_link_id`, `menu_link_order`, `menu_link_name`, `menu_link_type`, `fk_page_id`, `fk_post_id`, `fk_menu_id`) VALUES
(6, 1, 'tersfd', 1, 1, NULL, 1);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `page_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `page_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0=Disabled, 1=Enabled',
  `page_protected` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0=No, 1=Yes',
  `page_url_key` varchar(50) NOT NULL,
  `page_title` varchar(55) NOT NULL,
  `page_meta_robots` enum('noindex, follow','noindex, nofollow','index, follow','index, nofollow') NOT NULL DEFAULT 'noindex, follow',
  `page_meta_description` varchar(155) DEFAULT NULL,
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `page_url_key` (`page_url_key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Data dump for tabellen `pages`
--

INSERT INTO `pages` (`page_id`, `page_status`, `page_protected`, `page_url_key`, `page_title`, `page_meta_robots`, `page_meta_description`) VALUES
(1, 0, 1, 'asd', 'test', 'noindex, nofollow', 'test meta');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `page_content`
--

CREATE TABLE IF NOT EXISTS `page_content` (
  `page_content_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `page_content_order` tinyint(3) unsigned NOT NULL,
  `page_content_type` tinyint(1) unsigned NOT NULL,
  `page_content_description` varchar(255) DEFAULT NULL,
  `page_content` text,
  `fk_page_function_id` tinyint(3) unsigned DEFAULT NULL,
  `fk_page_layout_id` tinyint(3) unsigned NOT NULL,
  `fk_page_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`page_content_id`),
  KEY `fk_page_function_id` (`fk_page_function_id`),
  KEY `fk_page_id` (`fk_page_id`),
  KEY `fk_page_layout_id` (`fk_page_layout_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Data dump for tabellen `page_content`
--

INSERT INTO `page_content` (`page_content_id`, `page_content_order`, `page_content_type`, `page_content_description`, `page_content`, `fk_page_function_id`, `fk_page_layout_id`, `fk_page_id`) VALUES
(12, 1, 2, NULL, NULL, 2, 5, 1),
(13, 2, 1, 'test', '<p>te</p>\r\n', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `page_functions`
--

CREATE TABLE IF NOT EXISTS `page_functions` (
  `page_function_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `page_function_description` varchar(255) NOT NULL,
  `page_function_filename` varchar(50) NOT NULL,
  PRIMARY KEY (`page_function_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Data dump for tabellen `page_functions`
--

INSERT INTO `page_functions` (`page_function_id`, `page_function_description`, `page_function_filename`) VALUES
(1, 'Blog: Oversigt over indlæg', 'blog_oversigt.php'),
(2, 'Kontaktformular', 'kontakt.php');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `page_layouts`
--

CREATE TABLE IF NOT EXISTS `page_layouts` (
  `page_layout_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `page_layout_description` varchar(100) NOT NULL,
  `page_layout_class` varchar(50) NOT NULL,
  PRIMARY KEY (`page_layout_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Data dump for tabellen `page_layouts`
--

INSERT INTO `page_layouts` (`page_layout_id`, `page_layout_description`, `page_layout_class`) VALUES
(1, '100%', 'col-md-12'),
(2, '75%', 'col-md-9'),
(3, '66%', 'col-md-8'),
(4, '50%', 'col-md-6'),
(5, '33%', 'col-md-4'),
(6, '25%', 'col-md-3');

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_title` varchar(55) COLLATE utf8_danish_ci NOT NULL,
  `post_content` text COLLATE utf8_danish_ci NOT NULL,
  `post_meta_description` varchar(155) COLLATE utf8_danish_ci DEFAULT NULL,
  `post_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `post_url_key` varchar(50) COLLATE utf8_danish_ci NOT NULL,
  `post_status` tinyint(1) unsigned NOT NULL,
  `fk_user_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`post_id`),
  KEY `fk_user_id` (`fk_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci AUTO_INCREMENT=3 ;

--
-- Data dump for tabellen `posts`
--

INSERT INTO `posts` (`post_id`, `post_title`, `post_content`, `post_meta_description`, `post_time`, `post_url_key`, `post_status`, `fk_user_id`) VALUES
(1, 'Eksempel 1', 'asd', NULL, '2016-10-06 09:03:15', 'eksempel1', 0, 1),
(2, 'tester212', '<p>asddd</p>\r\n', NULL, '2016-10-07 07:19:21', 'asdtest', 1, 1);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `post_comments`
--

CREATE TABLE IF NOT EXISTS `post_comments` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comment_content` varchar(155) COLLATE utf8_danish_ci NOT NULL,
  `comment_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fk_user_id` mediumint(8) unsigned NOT NULL,
  `fk_post_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `fk_user_id` (`fk_user_id`,`fk_post_id`),
  KEY `fk_post_id` (`fk_post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_danish_ci AUTO_INCREMENT=5 ;

--
-- Data dump for tabellen `post_comments`
--

INSERT INTO `post_comments` (`comment_id`, `comment_content`, `comment_time`, `fk_user_id`, `fk_post_id`) VALUES
(1, '<p>tester</p>\r\n', '2016-10-07 06:41:47', 1, 1),
(4, '<p>asdtes</p>\r\n', '2016-10-07 08:00:49', 1, 1);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `role_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(25) NOT NULL,
  `role_access_level` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Data dump for tabellen `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `role_access_level`) VALUES
(1, 'SUPER_ADMINISTRATOR', 1000),
(2, 'ADMINISTRATOR', 100),
(3, 'MODERATOR', 10),
(4, 'USER', 1);

-- --------------------------------------------------------

--
-- Struktur-dump for tabellen `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0=Disabled, 1=Enabled',
  `user_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `fk_role_id` tinyint(3) unsigned NOT NULL DEFAULT '4',
  PRIMARY KEY (`user_id`),
  KEY `fk_role_id` (`fk_role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Data dump for tabellen `users`
--

INSERT INTO `users` (`user_id`, `user_status`, `user_created`, `user_name`, `user_email`, `user_password`, `fk_role_id`) VALUES
(1, 1, '2016-09-26 10:54:21', 'SuperUser', 'super@admin.dk', '$2y$10$9.yQapKx9cSjSlnZCgiFnuTx3A.gByPji1qjPwLRl7dd5aU1foRM6', 1),
(2, 1, '2016-09-26 10:55:26', 'Admin', 'admin@admin.dk', '$2y$10$9.yQapKx9cSjSlnZCgiFnuTx3A.gByPji1qjPwLRl7dd5aU1foRM6', 2),
(3, 1, '2016-09-26 10:55:42', 'Moderator', 'mod@admin.dk', '$2y$10$9.yQapKx9cSjSlnZCgiFnuTx3A.gByPji1qjPwLRl7dd5aU1foRM6', 3),
(4, 1, '2016-09-26 11:43:18', 'brugerss', 'bruger@notadmin.dk', '$2y$10$9.yQapKx9cSjSlnZCgiFnuTx3A.gByPji1qjPwLRl7dd5aU1foRM6', 4),
(5, 1, '2016-09-27 11:48:54', 'brugertester', 'tester@bruger.dk', '$2y$10$9.yQapKx9cSjSlnZCgiFnuTx3A.gByPji1qjPwLRl7dd5aU1foRM6', 4),
(6, 1, '2016-09-28 07:37:22', 'testbruger', 'bruger@erbruger.dk', '$2y$10$9.yQapKx9cSjSlnZCgiFnuTx3A.gByPji1qjPwLRl7dd5aU1foRM6', 4),
(7, 1, '2016-09-28 07:37:22', 'brugerigen', 'igen@enbruger.dk', '$2y$10$9.yQapKx9cSjSlnZCgiFnuTx3A.gByPji1qjPwLRl7dd5aU1foRM6', 4),
(8, 1, '2016-09-28 07:38:27', '123brug', '123@hjemmesidelort.dk', '$2y$10$9.yQapKx9cSjSlnZCgiFnuTx3A.gByPji1qjPwLRl7dd5aU1foRM6', 4),
(9, 0, '2016-09-28 07:38:27', 'wtfbruger', 'for@fanden.dk', '$2y$10$9.yQapKx9cSjSlnZCgiFnuTx3A.gByPji1qjPwLRl7dd5aU1foRM6', 4),
(10, 1, '2016-09-29 07:26:56', 'asder', 'testerbrugerNY@notadmin.dk', '$2y$10$YCkCV1jwwwzYL7P0ZEPP/OWQbWPyeyAeBz0yFHxNwWnQ3fi14RbSK', 4),
(11, 0, '2016-09-29 08:54:11', 'asd', 'asd@asdk.dk', '$2y$10$l05NC36JHPlgA3JlNQZj3OfR8mZKIWkk.OIP1yP7G0tsHYCoUTPwS', 4),
(13, 0, '2016-10-04 07:06:03', 'super2', 'super2@admin.dk', '$2y$10$VhJDVPTmz.3NEpxidSvZmO/r4SHL7xEPFRJURpLNEX4h54afhqXmS', 1),
(14, 1, '2016-10-04 07:12:30', 'admin2', 'admin2@admin.dk', '$2y$10$.khjr.KNKUcQJ7ltSSOXeu6rKMTOsgsirGamIEh3bp4yUSUshZ5Ii', 2),
(15, 0, '2016-10-04 09:04:36', 'asd', 'asd@asd.dk', '$2y$10$by2HeLCW.hHUFJMhJ3/lFO7kXriZyAl51QPkxswYSLoLgfYiV5.wK', 4);

--
-- Begrænsninger for dumpede tabeller
--

--
-- Begrænsninger for tabel `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`fk_event_type_id`) REFERENCES `event_types` (`event_type_id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`fk_user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Begrænsninger for tabel `menu_links`
--
ALTER TABLE `menu_links`
  ADD CONSTRAINT `menu_links_ibfk_1` FOREIGN KEY (`fk_menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `menu_links_ibfk_2` FOREIGN KEY (`fk_page_id`) REFERENCES `pages` (`page_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `menu_links_ibfk_3` FOREIGN KEY (`fk_post_id`) REFERENCES `posts` (`post_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Begrænsninger for tabel `page_content`
--
ALTER TABLE `page_content`
  ADD CONSTRAINT `page_content_ibfk_1` FOREIGN KEY (`fk_page_id`) REFERENCES `pages` (`page_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `page_content_ibfk_2` FOREIGN KEY (`fk_page_function_id`) REFERENCES `page_functions` (`page_function_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `page_content_ibfk_3` FOREIGN KEY (`fk_page_layout_id`) REFERENCES `page_layouts` (`page_layout_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Begrænsninger for tabel `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`fk_user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Begrænsninger for tabel `post_comments`
--
ALTER TABLE `post_comments`
  ADD CONSTRAINT `post_comments_ibfk_1` FOREIGN KEY (`fk_user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `post_comments_ibfk_2` FOREIGN KEY (`fk_post_id`) REFERENCES `posts` (`post_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Begrænsninger for tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`fk_role_id`) REFERENCES `roles` (`role_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
