<?php
include '../connection.php';
$sid = "22-1342";
$lastname = "RMX";
$firstname = "JHZ";
$mi = "A";
$yearsec = "2C";
$email = strtolower(str_replace(" ", "", $firstname)) . "." . strtolower(str_replace(" ", "", $lastname)) . "@cbsua.edu.ph";
$password = "cit-" . $sid;
$hash_password = password_hash($password, PASSWORD_DEFAULT);
$sql_student = "INSERT INTO `students`(`student_id`, `last_name`, `first_name`, `middle_initial`, `year_and_section`) VALUES (?, ?, ?, ?, ?)";
$stmt_student = $conn->prepare($sql_student);
$sql_account = "INSERT INTO `accounts`(`email`, `password`, `student_id`, `type`) VALUES (?, ?, ?, 'admin')";
$stmt_account = $conn->prepare($sql_account);
$stmt_student->bind_param("sssss", $sid, $lastname, $firstname, $mi, $yearsec);
$stmt_account->bind_param("sss", $email, $hash_password, $sid);
if ($stmt_student->execute() && $stmt_account->execute()) {
	echo "Admin account created";
}
?>