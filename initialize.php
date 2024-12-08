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

CREATE TABLE IF NOT EXISTS `students` (
  `student_id` varchar(7) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_initial` varchar(2) NOT NULL,
  `year_and_section` varchar(2) NOT NULL,
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `accounts` (
  `account_id` int(5) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `student_id` varchar(7) NOT NULL,
  `type` varchar(5) NOT NULL,
  PRIMARY KEY (`account_id`),
  FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `events` (
  `event_id` int(5) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(100) NOT NULL,
  `event_description` varchar(255) NOT NULL,
  `event_target` set('1','2','3','4') NOT NULL,
  `event_date` date NOT NULL,
  `event_fee` int(6) NOT NULL,
  `sanction_fee` int(3) NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `registrations` (
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

CREATE TABLE IF NOT EXISTS `sanctions` (
  `sanction_id` int(5) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(7) NOT NULL,
  `event_id` int(5) NOT NULL,
  `sanctions_paid` int(3) NOT NULL,
  PRIMARY KEY (`sanction_id`),
  FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE,
  FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE
) ENGINE=InnoDB;
";

$stored_function_sql = "
CREATE FUNCTION IF NOT EXISTS `getYearlyStudentCount`(year_level CHAR(1)) RETURNS int(11)
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
CREATE PROCEDURE IF NOT EXISTS `getTotalAmountPaid`(OUT total_amount_paid DECIMAL(10,2))
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
  $conn->query("CREATE DATABASE IF NOT EXISTS `$db`");
  $conn = new PDO("mysql:host=$host;dbname=$db", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conn->exec($import_sql);
  $conn->exec($stored_function_sql);
  $conn->exec($stored_procedure_sql);
  echo "Database has been initialized.<br>";
	$stmt_student = $conn->prepare($sql_student);
	$stmt_account = $conn->prepare($sql_account);
	$stmt_student->execute([$sid, $lastname, $firstname, $mi, $yearsec]);
  $stmt_account->execute([$email, $hash_password, $sid]);
  echo "Admin account created:<br>Email: ".$email."<br>Password: cit-".$sid;
} catch (PDOException $e) {
	echo "An error occured: ".$e->getMessage();
  echo "<br>Admin account already exists";
}
?>