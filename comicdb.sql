-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 03, 2015 at 09:13 AM
-- Server version: 5.5.44-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `comicdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--

CREATE TABLE IF NOT EXISTS `artists` (
  `artist_id` int(3) NOT NULL AUTO_INCREMENT,
  `artist_name` varchar(50) NOT NULL,
  PRIMARY KEY (`artist_id`),
  UNIQUE KEY `artist_id` (`artist_id`,`artist_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `artist_link`
--

CREATE TABLE IF NOT EXISTS `artist_link` (
  `comic_id` int(5) NOT NULL,
  `artist_id` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comics`
--

CREATE TABLE IF NOT EXISTS `comics` (
  `comic_id` int(5) NOT NULL AUTO_INCREMENT,
  `series_id` int(3) NOT NULL,
  `issue_number` int(3) NOT NULL,
  `story_name` varchar(200) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `plot` varchar(10000) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `original_purchase` tinyint(1) NOT NULL,
  `wiki_id` int(8) DEFAULT NULL,
  `wikiUpdated` int(1) DEFAULT NULL,
  PRIMARY KEY (`comic_id`),
  UNIQUE KEY `comic_id` (`comic_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=270 ;

-- --------------------------------------------------------

--
-- Table structure for table `series`
--

CREATE TABLE IF NOT EXISTS `series` (
  `series_id` int(3) NOT NULL AUTO_INCREMENT,
  `series_name` varchar(100) NOT NULL,
  PRIMARY KEY (`series_id`),
  UNIQUE KEY `series_name` (`series_name`),
  UNIQUE KEY `series_id` (`series_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `storylines`
--

CREATE TABLE IF NOT EXISTS `storylines` (
  `storyline_id` int(3) NOT NULL AUTO_INCREMENT,
  `storyline_name` varchar(100) NOT NULL,
  PRIMARY KEY (`storyline_id`),
  UNIQUE KEY `storyline_id` (`storyline_id`),
  UNIQUE KEY `storyline_name` (`storyline_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `storyline_link`
--

CREATE TABLE IF NOT EXISTS `storyline_link` (
  `comic_id` int(5) NOT NULL,
  `storyline_id` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index',
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name, unique',
  `user_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s password in salted and hashed format',
  `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s email, unique',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `writers`
--

CREATE TABLE IF NOT EXISTS `writers` (
  `writer_id` int(3) NOT NULL AUTO_INCREMENT,
  `writer_name` varchar(50) NOT NULL,
  PRIMARY KEY (`writer_id`),
  UNIQUE KEY `writer_id` (`writer_id`,`writer_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `writer_link`
--

CREATE TABLE IF NOT EXISTS `writer_link` (
  `comic_id` int(5) NOT NULL,
  `writer_id` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
