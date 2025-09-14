<?php
session_start();

$_SESSION = [];

// destroy session
session_destroy();

// redirect login
header('Location: /pages/login/index.php');
exit;
?>
