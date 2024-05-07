<?php
session_destroy();
setcookie('cit-emailemail', '', time() - 3600, '/');
setcookie('cit-password', '', time() - 3600, '/');
header("location: index.php");
?>