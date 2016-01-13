SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `comicdb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `comicdb`;

DROP TABLE IF EXISTS `artists`;
CREATE TABLE IF NOT EXISTS `artists` (
  `artist_id` int(3) NOT NULL,
  `artist_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `artist_link`;
CREATE TABLE IF NOT EXISTS `artist_link` (
  `comic_id` int(5) NOT NULL,
  `artist_id` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `comics`;
CREATE TABLE IF NOT EXISTS `comics` (
  `comic_id` int(5) NOT NULL,
  `series_id` int(3) NOT NULL,
  `issue_number` int(3) NOT NULL,
  `story_name` varchar(200) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `plot` varchar(10000) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `ownerID` int(4) NOT NULL,
  `original_purchase` tinyint(1) NOT NULL,
  `wiki_id` int(8) NOT NULL DEFAULT '0',
  `wikiUpdated` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `series`;
CREATE TABLE IF NOT EXISTS `series` (
  `series_id` int(3) NOT NULL,
  `series_name` varchar(100) NOT NULL,
  `series_vol` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `storylines`;
CREATE TABLE IF NOT EXISTS `storylines` (
  `storyline_id` int(3) NOT NULL,
  `storyline_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `storyline_link`;
CREATE TABLE IF NOT EXISTS `storyline_link` (
  `comic_id` int(5) NOT NULL,
  `storyline_id` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL COMMENT 'auto incrementing user_id of each user, unique index',
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name, unique',
  `user_password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s password in salted and hashed format',
  `user_email` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s email, unique'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data';

DROP TABLE IF EXISTS `writers`;
CREATE TABLE IF NOT EXISTS `writers` (
  `writer_id` int(3) NOT NULL,
  `writer_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `writer_link`;
CREATE TABLE IF NOT EXISTS `writer_link` (
  `comic_id` int(5) NOT NULL,
  `writer_id` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `artists`
  ADD PRIMARY KEY (`artist_id`),
  ADD UNIQUE KEY `artist_id` (`artist_id`,`artist_name`);

ALTER TABLE `comics`
  ADD PRIMARY KEY (`comic_id`),
  ADD UNIQUE KEY `comic_id` (`comic_id`);

ALTER TABLE `series`
  ADD PRIMARY KEY (`series_id`),
  ADD UNIQUE KEY `series_name` (`series_name`),
  ADD UNIQUE KEY `series_id` (`series_id`);

ALTER TABLE `storylines`
  ADD PRIMARY KEY (`storyline_id`),
  ADD UNIQUE KEY `storyline_id` (`storyline_id`),
  ADD UNIQUE KEY `storyline_name` (`storyline_name`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD UNIQUE KEY `user_email` (`user_email`);

ALTER TABLE `writers`
  ADD PRIMARY KEY (`writer_id`),
  ADD UNIQUE KEY `writer_id` (`writer_id`,`writer_name`);


ALTER TABLE `artists`
  MODIFY `artist_id` int(3) NOT NULL AUTO_INCREMENT;
ALTER TABLE `comics`
  MODIFY `comic_id` int(5) NOT NULL AUTO_INCREMENT;
ALTER TABLE `series`
  MODIFY `series_id` int(3) NOT NULL AUTO_INCREMENT;
ALTER TABLE `storylines`
  MODIFY `storyline_id` int(3) NOT NULL AUTO_INCREMENT;
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index';
ALTER TABLE `writers`
  MODIFY `writer_id` int(3) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
