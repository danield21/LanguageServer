<?php
session_start();
require_once 'php/config.php';
unset($_SESSION['language_server_user']);
unset($_SESSION['language_server_key']);
header('Location: .');
die("You should never see this");
?>