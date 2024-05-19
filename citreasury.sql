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
  KEY `accounts_student_id_students_student_id` (`student_id`),
  CONSTRAINT `accounts_student_id_students_student_id` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`)
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
  `fee_per_event` int(6) NOT NULL,
  `sanction_fee` int(3) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `events` (`event_id`, `event_name`, `event_description`, `event_date`, `fee_per_event`, `sanction_fee`) VALUES
(101, 'IT Night', 'Baraylehan', '2024-05-17', 200,  20),
(102, 'General Assembly', 'Saaga',  '2024-05-20', 0,  30);

DROP TABLE IF EXISTS `registrations`;
CREATE TABLE `registrations` (
  `registration_id` int(5) NOT NULL AUTO_INCREMENT,
  `event_id` int(5) NOT NULL,
  `student_id` varchar(7) NOT NULL,
  `registration_date` date NOT NULL,
  `paid_fees` int(11) NOT NULL,
  PRIMARY KEY (`registration_id`),
  KEY `registrations_event_id_events_event_id` (`event_id`),
  KEY `registrations_student_id_students_student_id` (`student_id`),
  CONSTRAINT `registrations_event_id_events_event_id` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`),
  CONSTRAINT `registrations_student_id_students_student_id` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `registrations` (`registration_id`, `event_id`, `student_id`, `registration_date`, `paid_fees`) VALUES
(4, 101,  '22-0880',  '2024-05-15', 200),
(10,  101,  '22-1677',  '2024-05-16', 30),
(11,  102,  '22-1677',  '2024-05-19', 0);

-- 2024-05-19 01:46:25