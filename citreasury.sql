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
('21-6394', 'Ubnlbk', 'Ihgrsfda', 'O',  '3A'),
('22-0880', 'Menor',  'Ana Mae',  'M',  '2C'),
('22-1342', 'Bobis',  'Jaspher Jed',  'A',  '2C'),
('22-1677', 'Gamboa', 'Jerick', 'D',  '2C'),
('23-9563', 'Htdgrsg',  'Tdrgsfd',  'G',  '1E');

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `account_id` int(5) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `student_id` varchar(7) NOT NULL,
  `type` varchar(5) NOT NULL,
  PRIMARY KEY (`account_id`),
  FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `accounts` (`account_id`, `email`, `password`, `student_id`, `type`) VALUES
(1, 'jaspherjed.bobis@cbsua.edu.ph',  'cit-22-1342',  '22-1342',  'admin'),
(10,  'jerick.gamboa@cbsua.edu.ph', 'cit-22-1677',  '22-1677',  'user'),
(18,  'anamae.menor@cbsua.edu.ph',  'cit-22-0880',  '22-0880',  'user'),
(19,  'tdrgsfd.htdgrsg@cbsua.edu.ph', 'cit-23-9563',  '23-9563',  'user'),
(20,  'ihgrsfda.ubnlbk@cbsua.edu.ph', 'cit-21-6394',  '21-6394',  'user');

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `event_id` int(5) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(100) NOT NULL,
  `event_description` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `event_fee` int(6) NOT NULL,
  `sanction_fee` int(3) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `events` (`event_id`, `event_name`, `event_description`, `event_date`, `event_fee`, `sanction_fee`) VALUES
(101, 'IT Night', 'Aranuhan', '2024-05-19', 200,  20),
(102, 'General Assembly', 'Basta',  '2024-05-21', 0,  40),
(103, 'Arts Month', 'Aranuhan', '2024-05-19', 100,  40);

DROP TABLE IF EXISTS `registrations`;
CREATE TABLE `registrations` (
  `registration_id` int(5) NOT NULL AUTO_INCREMENT,
  `event_id` int(5) NOT NULL,
  `student_id` varchar(7) NOT NULL,
  `registration_date` date NOT NULL,
  `paid_fees` int(11) NOT NULL,
  `status` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`registration_id`),
  FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`),
  FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `registrations` (`registration_id`, `event_id`, `student_id`, `registration_date`, `paid_fees`, `status`) VALUES
(4, 101,  '22-0880',  '2024-05-15', 200,  'FULLY_PAID_BEFORE_EVENT'),
(14,  101,  '22-1677',  '2024-05-18', 220,  NULL),
(16,  102,  '22-1677',  '2024-05-21', 0,  'FULLY_PAID_BEFORE_EVENT'),
(17,  102,  '22-0880',  '2024-05-21', 0,  'FULLY_PAID_BEFORE_EVENT');

DROP TABLE IF EXISTS `sanctions`;
CREATE TABLE `sanctions` (
  `sanction_id` int(5) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(7) NOT NULL,
  `event_id` int(5) NOT NULL,
  `sanctions_paid` int(3) NOT NULL,
  PRIMARY KEY (`sanction_id`),
  FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`),
  FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- 2024-05-20 18:23:29