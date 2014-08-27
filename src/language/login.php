<?php
session_start();
require_once 'php/all.php';

if(isset($_GET['redirect']) && !isset($_SESSION['language_server_user'])) {
	$acount_table = new AccountsTable(IP, USER, PASSWORD, DATABASE);
	$token = $acount_table->authorize($_POST['user'], $_POST['pass']);
	if($token) {
		$_SESSION['language_server_user'] = $token;
		$_SESSION['language_server_key'] = rand();
	} else {
		$_GET['redirect'] .= '?bad_password=1';
	}
}
//Redirect back to website
header('Location: ' . $_GET['redirect']);
die('If you are not redirected, press this <a href="' . $_GET['redirect'] . '">link<\a>');
?>
