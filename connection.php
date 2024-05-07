<?php
$host = "localhost";
$username = "root";
$password = "";
$db = "citreasury";
$conn = new mysqli($host, $username, $password, $db);
if ($conn->connect_error) {
    die("<script>alert('Connection failed.')</script>");
}
?>