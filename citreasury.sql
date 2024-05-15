-- Adminer 4.8.1 MySQL 5.6.45 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `citreasury`;
CREATE DATABASE `citreasury` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `citreasury`;

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `student_id` varchar(7) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_initial` varchar(2) NOT NULL,
  `year_and_section` varchar(2) NOT NULL,
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `students` (`student_id`, `last_name`, `first_name`, `middle_initial`, `year_and_section`) VALUES
('22-1342', 'Bobis',  'Jaspher Jed',  'A',  '2C'),
('22-1677', 'Gamboa', 'Jerick', 'D',  '2C');

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `account_id` int(5) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `student_id` varchar(7) NOT NULL,
  `type` varchar(5) NOT NULL,
  PRIMARY KEY (`account_id`),
  KEY `accounts_student_id_students_student_id` (`student_id`),
  CONSTRAINT `accounts_student_id_students_student_id` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `accounts` (`account_id`, `email`, `password`, `student_id`, `type`) VALUES
(1, 'jaspherjed.bobis@cbsua.edu.ph',  'cit-22-1342',  '22-1342',  'admin'),
(10,  'jerick.gamboa@cbsua.edu.ph', 'cit-22-1677',  '22-1677',  'user');

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `event_id` int(5) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(100) NOT NULL,
  `event_description` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `fee_per_event` int(6) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `registrations`;
CREATE TABLE `registrations` (
  `registration_id` int(5) NOT NULL AUTO_INCREMENT,
  `event_id` int(5) NOT NULL,
  `student_id` varchar(7) NOT NULL,
  `registration_date` date NOT NULL,
  `advance_fee` int(11) NOT NULL,
  PRIMARY KEY (`registration_id`),
  KEY `registrations_event_id_events_event_id` (`event_id`),
  KEY `registrations_student_id_students_student_id` (`student_id`),
  CONSTRAINT `registrations_event_id_events_event_id` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`),
  CONSTRAINT `registrations_student_id_students_student_id` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- 2024-05-15 10:38:53