# Database

<!-- This is where you will place information about your database:
- Structure
- SQL dump
- etc. -->

## General structure
The database for my website uses 4 tables.  
A supercategory is a table of categories, which are tables of tasks, which are tables of subtasks.  
The foreign key of subtask is set to a task, a tasks foreign key is a category, and a categoires foreign key is a supercategory.  
Categories, tasks and subtasks are set to cascade on deletion, so that deleting a table higher in the hierarchy will remove the descendants of that table.

## Supercategory
A supercategory contains an auto incrementing id (unsigned integer), which is used as supercategory names may not always be unique.  
A supercategories name is a varchar capped at 127 characters. They also have another unsigned integer which controls the order of the supercategory in the list.
The order is ascending, so supercategories with higher orders show higher in the list.

## Category
A category has the same data structure as a supercategory, with the addition of the foreign key for the supercategory it belongs to.

## Task
A task has an unsigned integer id, as again there may be more than one task sharing the same name. The tasks name can be 255 characters long. A task has a description field which is a text datatype, (up to 65536 characters). Tasks share the same order system as categories and supercategories. A task has a float which determins the percentage of subtasks that are completed. While this completion field isnt necessary, it makes it simpler when loading the website as you do not need to check the completion of each subtask of a task while generating the task list. A task had a foreign key for the category it belongs to.

## Subtask
A subtask also uses an auto incrementing id (unsigned integer) as there will likely be duplicate names. They are represented by a task field, which is a text datatype. This describes what needs to be done to complete this subtask. Subtasks may optionally contain an image, which is stored as two keys, one is image_type which is the MIME type of the image, and the other is a medium blob, which stores the binary data of the image. These two keys may be null. There is another optional deadline key, which is a datetime. This will be stored as a UTC datetime, and offset to the local users timezone when rendering the page. There is a boolean (unsigned tinyint) to indicate if the task has been completed or not, when this updates, it will update the completion float on the task this subtask is linked to. A subtask has a foreign key for the task it belongs to.

## SQL Dump
<details>
    <summary>Click to expand</summary>
  
  ```sql
    -- Adminer 4.8.4 MySQL 8.0.39-0ubuntu0.22.04.1 dump

    SET NAMES utf8;
    SET time_zone = '+00:00';
    SET foreign_key_checks = 0;
    SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

    SET NAMES utf8mb4;

    DROP DATABASE IF EXISTS `jrgerraty_200_Game_Development_Manager`;
    CREATE DATABASE `jrgerraty_200_Game_Development_Manager` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
    USE `jrgerraty_200_Game_Development_Manager`;

    DROP TABLE IF EXISTS `category`;
    CREATE TABLE `category` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `order` int unsigned NOT NULL,
    `name` varchar(127) COLLATE utf8mb4_general_ci NOT NULL,
    `supercategory` int unsigned NOT NULL,
    PRIMARY KEY (`id`),
    KEY `supercategory` (`supercategory`),
    CONSTRAINT `category_ibfk_2` FOREIGN KEY (`supercategory`) REFERENCES `supercategory` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    INSERT INTO `category` (`id`, `order`, `name`, `supercategory`) VALUES
    (2,	1,	'archived',	1),
    (29,	1,	'Category',	2),
    (30,	2,	'second',	2),
    (31,	1,	'1',	13),
    (32,	2,	'2',	13),
    (33,	3,	'3',	13),
    (34,	4,	'4',	13),
    (35,	5,	'5',	13);

    DROP TABLE IF EXISTS `subtask`;
    CREATE TABLE `subtask` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `task` text COLLATE utf8mb4_general_ci NOT NULL,
    `image_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
    `image_data` mediumblob,
    `deadline` datetime DEFAULT NULL,
    `linked` int unsigned NOT NULL,
    `completed` tinyint unsigned NOT NULL,
    PRIMARY KEY (`id`),
    KEY `linked` (`linked`),
    CONSTRAINT `subtask_ibfk_1` FOREIGN KEY (`linked`) REFERENCES `tasks` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


    DROP TABLE IF EXISTS `supercategory`;
    CREATE TABLE `supercategory` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(127) COLLATE utf8mb4_general_ci NOT NULL,
    `order` int unsigned NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    INSERT INTO `supercategory` (`id`, `name`, `order`) VALUES
    (1,	'archived',	0),
    (2,	'General',	1),
    (13,	'supercategory',	2);

    DROP TABLE IF EXISTS `tasks`;
    CREATE TABLE `tasks` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
    `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `completion` float unsigned NOT NULL DEFAULT '0',
    `category` int unsigned NOT NULL,
    `order` int unsigned NOT NULL,
    PRIMARY KEY (`id`),
    KEY `category` (`category`),
    CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`category`) REFERENCES `category` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

    INSERT INTO `tasks` (`id`, `name`, `description`, `completion`, `category`, `order`) VALUES
    (3,	'test',	'test description',	0,	30,	2),
    (4,	'help with my flower',	'test',	0,	30,	2);

    -- 2024-08-07 10:06:00
  ```
</details>
