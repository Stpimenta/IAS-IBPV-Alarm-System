<?php
session_start();

$_SESSION = [];

// destroy session
session_destroy();

// redirect login
header('Location: index.php'); // ou '../login/index.php' dependendo do caminho
exit;
?>
