<?php
session_destroy();
setcookie('cit-email', '', time() - 3600, '/');
setcookie('cit-password', '', time() - 3600, '/');
setcookie('cit-type', '', time() - 3600, '/');
header("location: index.php");
?>