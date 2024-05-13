<?php
session_destroy();
setcookie('cit-student-id', '', time() + (86400 * 30), '/');
header("location: index.php");
?>