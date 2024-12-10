<?php
include 'password_compat.php';

$host = "localhost";
$username = "root";
$password = "";
$db = "citreasury";

# BEGIN TEST CREDENTIALS
$sid = "22-1342";
$lastname = "RMX";
$firstname = "JHZ";
$mi = "A";
$yearsec = "3A";
$email = strtolower(str_replace(" ", "", $firstname)) . "." . strtolower(str_replace(" ", "", $lastname)) . "@cbsua.edu.ph";
$hash_password = password_hash("cit-" . $sid, PASSWORD_DEFAULT);
# END TEST CREDENTIALS
$sql_student = "INSERT INTO `students`(`student_id`, `last_name`, `first_name`, `middle_initial`, `year_and_section`) VALUES (?, ?, ?, ?, ?)";
$sql_account = "INSERT INTO `accounts`(`email`, `password`, `student_id`, `type`) VALUES (?, ?, ?, 'admin')";

$import_sql = "SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `student_id` varchar(7) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_initial` varchar(2) NOT NULL,
  `year_and_section` varchar(2) NOT NULL,
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB;

INSERT INTO `students` (`student_id`, `last_name`, `first_name`, `middle_initial`, `year_and_section`) VALUES
('21-6394', 'Ubnlbk', 'Ihgrsfda', 'O',  '3A'),
('22-0880', 'Menor',  'Ana Mae',  'M',  '2C'),
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
  FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO `accounts` (`account_id`, `email`, `password`, `student_id`, `type`) VALUES
(10,  'jerick.gamboa@cbsua.edu.ph', '".password_hash('cit-22-1677', PASSWORD_DEFAULT)."',  '22-1677',  'user'),
(18,  'anamae.menor@cbsua.edu.ph',  '".password_hash('cit-22-0880', PASSWORD_DEFAULT)."',  '22-0880',  'user'),
(19,  'tdrgsfd.htdgrsg@cbsua.edu.ph', '".password_hash('cit-23-9563', PASSWORD_DEFAULT)."',  '23-9563',  'user'),
(20,  'ihgrsfda.ubnlbk@cbsua.edu.ph', '".password_hash('cit-21-6394', PASSWORD_DEFAULT)."',  '21-6394',  'user');

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `event_id` int(5) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(100) NOT NULL,
  `event_description` varchar(255) NOT NULL,
  `event_target` set('1','2','3','4') NOT NULL,
  `event_date` date NOT NULL,
  `event_fee` int(6) NOT NULL,
  `sanction_fee` int(3) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB;

INSERT INTO `events` (`event_id`, `event_name`, `event_description`, `event_target`, `event_date`, `event_fee`, `sanction_fee`) VALUES
(101, 'IT Night', 'Aranuhan', '1,2,3,4',  '2024-05-19', 200,  20),
(102, 'General Assembly', 'Basta',  '1,2,3,4',  '2024-05-21', 0,  40),
(103, 'Arts Month', 'Nugagawen',  '1,2,3,4',  '2024-05-19', 100,  40),
(104, 'Tribute To Seniors', 'Basta may baraylehan', '3,4',  '2025-02-14', 120,  20);

DROP TABLE IF EXISTS `registrations`;
CREATE TABLE `registrations` (
  `registration_id` int(5) NOT NULL AUTO_INCREMENT,
  `event_id` int(5) NOT NULL,
  `student_id` varchar(7) NOT NULL,
  `registration_date` date NOT NULL,
  `paid_fees` int(11) NOT NULL,
  `status` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`registration_id`),
  FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE,
  FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE
) ENGINE=InnoDB;

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
  FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE,
  FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE
) ENGINE=InnoDB;

DROP FUNCTION IF EXISTS `getYearlyStudentCount`;
DROP PROCEDURE IF EXISTS `getTotalAmountPaid`;
";

$stored_function_sql = "
CREATE FUNCTION `getYearlyStudentCount`(year_level CHAR(1)) RETURNS int(11)
    DETERMINISTIC
BEGIN
    DECLARE student_count INT;
    SELECT COUNT(`student_id`) 
    INTO student_count 
    FROM `students` 
    WHERE `year_and_section` LIKE CONCAT(year_level, '%');
    RETURN student_count;
END;
";

$stored_procedure_sql = "
CREATE PROCEDURE `getTotalAmountPaid`(OUT total_amount_paid DECIMAL(10,2))
BEGIN
  SELECT IFNULL(SUM(total_paid), 0)
  INTO total_amount_paid
  FROM (
      SELECT SUM(`paid_fees`) AS total_paid FROM `registrations`
      UNION ALL
      SELECT SUM(`sanctions_paid`) AS total_paid FROM `sanctions`
  ) AS combined_payments;
END;
";

try {
  $conn = new PDO("mysql:host=$host", $username, $password);
  $conn->query("DROP DATABASE IF EXISTS `$db`");
  $conn->query("CREATE DATABASE `$db`");
  $conn = new PDO("mysql:host=$host;dbname=$db", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->exec($import_sql);
  $conn->exec($stored_function_sql);
  $conn->exec($stored_procedure_sql);
  echo "Database has been reset.<br><br>";
	$stmt_student = $conn->prepare($sql_student);
	$stmt_account = $conn->prepare($sql_account);
	if ($stmt_student->execute([$sid, $lastname, $firstname, $mi, $yearsec]) && $stmt_account->execute([$email, $hash_password, $sid])) {
		echo "Admin account created:<br>Email: ".$email."<br>Password: cit-".$sid;
	} else {
		echo "An error occured.";
	}
} catch (PDOException $e) {
	echo "An error occured: ".$e->getMessage();
}
?>