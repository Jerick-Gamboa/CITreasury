<?php
# Default MYSQL database connection
$host = "localhost";
$username = "root";
$password = "";
$db = "citreasury";
$conn = null;

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    $response = [];
    $response["status"] = "error";
    $response["message"] = "Connection Failed";
    $response["details"] = $e->getMessage();
    echo json_encode($response);
    exit();
}
?>