DROP DATABASE IF EXISTS `doingsdone`;

CREATE DATABASE `doingsdone`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;

USE `doingsdone`;

CREATE TABLE `user` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `date` timestamp DEFAULT CURRENT_TIMESTAMP,
  `login` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,

  UNIQUE INDEX `user_login` (`login`),
  UNIQUE INDEX `user_email` (`email`)
);

CREATE TABLE `project` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `date` timestamp DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(128) NOT NULL,
  `user_id` int(11) NOT NULL,

  UNIQUE INDEX `project_name` (name),

  CONSTRAINT `project_fk_user_id` FOREIGN KEY (`user_id`) REFERENCES user(`id`) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE `task` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `date` timestamp DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(128) NOT NULL,
  `is_ready` tinyint(1) NOT NULL DEFAULT 0,
  `file_url` varchar(255) NULL DEFAULT NULL,
  `finish_date` timestamp,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,

  CONSTRAINT `task_fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `task_fk_project_id` FOREIGN KEY (`project_id`) REFERENCES `project`(`id`) ON UPDATE CASCADE ON DELETE CASCADE
);
