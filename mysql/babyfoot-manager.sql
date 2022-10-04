-- Adminer 4.8.1 MySQL 8.0.30 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE DATABASE `babyfoot_manager` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `babyfoot_manager`;

DROP TABLE IF EXISTS `babyfoot_manager`;
CREATE TABLE `babyfoot_manager` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name_player1` text NOT NULL,
  `name_player2` text NOT NULL,
  `score_player1` text NOT NULL,
  `score_player2` text NOT NULL,
  `user_id` int NOT NULL,
  `status` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

TRUNCATE `babyfoot_manager`;

-- 2022-10-04 13:55:01