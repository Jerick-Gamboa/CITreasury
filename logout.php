<?php
session_destroy();
# Delete cookies
setcookie('cit-student-id', '', time() + (86400 * 30), '/');
header("location: index.php");
?>