<?php
session_start();
require_once 'php/account_info.php';
require_once 'php/config.php';

if(isset($_GET['redirect']) && !isset($_SESSION['language_server_user'])) {
	//Login Details
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		echo "Cannot establish connection: (" . $connection->errno . ") " . $connection->error;
	}
	$command = 'SELECT * FROM accounts WHERE username = ? && password = ? LIMIT 0, 1;';
	
	if(!($stmt = $connection->prepare($command)))
	{
		echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;
	}
	$hash_pass = hash_password($_POST['user'], $_POST['pass']);
	if(!$stmt->bind_param('ss', $_POST['user'], $hash_pass))
	{
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	elseif (!$stmt->execute())
	{
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	$results = $stmt->get_result();
	$connection->close();
    if($results->num_rows != 0) {
	    $results->data_seek(0);
		$result = $results->fetch_assoc();
		$_SESSION['language_server_user'] = $result['username'];
		$_SESSION['language_server_rand_ID'] = rand();
		$_SESSION['language_server_' . $_SESSION['language_server_rand_ID']] = $result['account_id'];
    } else {
		$_GET['redirect'] .= '?bad_password=1';
	}
}
//Redirect back to website
header('Location: ' . $_GET['redirect']);
die("You should never see this");
?>