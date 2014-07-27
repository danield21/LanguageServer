<?php
session_start();
require_once 'php/config.php';
unset($_SESSION['language_server_user']);
unset($_SESSION['language_server_' . $_SESSION['rand_ID']]);
unset($_SESSION['language_server_rand_ID']);
header('Location: ' . ROOT);
die("You should never see this");
?>