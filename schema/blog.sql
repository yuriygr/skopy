SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `post`;
CREATE TABLE `post` (
	`id` int(20) unsigned NOT NULL AUTO_INCREMENT,
	`slug` varchar(100) NOT NULL,
	`subject` varchar(100) NOT NULL,
	`short_text` text NOT NULL,
	`full_text` text,
	`created_at` int(10) unsigned NOT NULL,
	`modified_in` int(10) unsigned NOT NULL,
	`is_draft` tinyint(3) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(20) NOT NULL,
	`email` varchar(100) NOT NULL,
	`rang` varchar(50) NOT NULL DEFAULT 'user',
	`login` varchar(50) NOT NULL,
	`password` varchar(50) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `name`, `email`, `rang`, `login`, `password`) VALUES
(1, 'admin',  'admin@gmail.com',  'admin',  'admin',  '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8');