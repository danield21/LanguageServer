<?php
session_start();
require_once 'php/account_info.php';
require_once 'php/config.php';

if(isset($_GET['redirect']) && !isset($_SESSION['language_server_user'])) {
		
	try {
		$connect = new PDO('mysql:host=' . IP . '; dbname=' . DATABASE . ';charset=utf8', USER, PASSWORD);
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		
		$command = 'SELECT * FROM accounts WHERE username = :user && password = :pass LIMIT 0, 1;';
		
		$stmt = $connect->prepare($command);
		$stmt->bindValue(':user', $_POST['user'], PDO::PARAM_STR);
		$stmt->bindValue(':pass', hash_password($_POST['user'], $_POST['pass']), PDO::PARAM_STR);
		$stmt->execute();
		
		$result = $stmt->fetchAll();
		
		if(isset($result[0]))
		{
			$_SESSION['language_server_user'] = $result[0]['username'];
			$_SESSION['language_server_rand_ID'] = rand();
			$_SESSION['language_server_' . $_SESSION['language_server_rand_ID']] = $result[0]['account_id'];
		}
		
	} catch(PDOException $ex) {
		$_GET['redirect'] .= '?bad_password=1';
		log_info($ex->getMessage());
	}
}
//Redirect back to website
header('Location: ' . $_GET['redirect']);
die('If you are not redirected, press this <a href="' . $_GET['redirect'] . '">link<\a>');
?>
