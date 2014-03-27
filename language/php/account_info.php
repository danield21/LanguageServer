<?php
	function hash_password($username, $password) {
		if(!isset($password) || $password === '') {
			return md5($username);
		}
		
		$hashed_user = md5($username);
		$hashed_pass = md5($password);
		$hash = "";
		for ($i=0; $i < 32; ++$i) { 
			$hash .= $hashed_user[$i] . $hashed_pass[$i];
		}
		return md5($hash);
	}
	
	function status() {
		if(!isset($_SESSION['language_server_rand_ID']) || !isset($_SESSION['language_server_' . $_SESSION['language_server_rand_ID']])) {
			return 0;
		}
		
		$id = $_SESSION['language_server_' . $_SESSION['language_server_rand_ID']];
		$user = $_SESSION['language_server_user'];
		
		$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
		if ($connection->connect_errno)
		{
			echo "Cannot establish connection: (" . $connection->errno . ") " . $connection->error;
			return 0;
		}
		$command = 'SELECT admin FROM accounts WHERE username = ? && account_id = ? LIMIT 0, 1;';
	
		if(!($stmt = $connection->prepare($command)))
		{
			echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;
			return 0;
		}
		if(!$stmt->bind_param('ss', $user, $id))
		{
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			return false;
		}
		elseif (!$stmt->execute())
		{
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			return false;
		}
		$results = $stmt->get_result();
		$connection->close();
    	if($results->num_rows != 0) {
	    	$results->data_seek(0);
			$result = $results->fetch_assoc();
			return $result['admin'];
		}
		else {
			return false;
		}
	}
	
	function status_username($username) {
		if(!isset($username)) {
			return 0;
		}
		
		$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
		if ($connection->connect_errno)
		{
			echo "Cannot establish connection: (" . $connection->errno . ") " . $connection->error;
			return 0;
		}
		$command = 'SELECT admin FROM accounts WHERE username = ? LIMIT 0, 1;';
		
		if(!($stmt = $connection->prepare($command)))
		{
			echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;
			return 0;
		}
		if(!$stmt->bind_param('s', $username))
		{
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			return 0;
		}
		elseif (!$stmt->execute())
		{
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			return 0;
		}
		$results = $stmt->get_result();
		$connection->close();
    	if($results->num_rows != 0) {
	    	$results->data_seek(0);
			$result = $results->fetch_assoc();
			return $result['admin'];
		}
		else {
			return 0;
		}
	}
	
	function get_all_accounts() {
		$status = status();
		$command = "";
		
		if($status) {
			if($status == 2) {
				$command = "SELECT username, first_name, last_name, admin FROM accounts;";
			} else {
				$command = "SELECT username, first_name, last_name FROM accounts;";
			}
		} else {
			return array();
		}
		
		$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
		if ($connection->connect_errno)
		{
			return array();
		}
			
		$results = $connection->query($command);
		$connection->close();
			
		$users = array();
			
		for($i = 0; $i < $results->num_rows; $i++)
		{
			$results->data_seek($i);
			$users[$i] = $results->fetch_assoc();
		}
		return $users;
	}
	
	function change_password($username, $password, $comfirm_pass) {
		$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
		if ($connection->connect_errno)
		{
			return false;
		}
		
		if(
				(!isset($password) || $password === '')
				|| (!isset($comfirm_pass) || $comfirm_pass === '')
				|| !($password === $comfirm_pass)
		) {
			return false;
		}
		
		$status = status();
		
		if((status_username($username) >= $status) && ($status != 2)) {
			return false;
		}
		
		$command = "UPDATE accounts SET password=? WHERE username=?;";
		
		if(!($stmt = $connection->prepare($command))) {
			return false;
		}
		if(!$stmt->bind_param('ss', hash_password($username, $password), $username)) {
			return false;
		}
		elseif (!$stmt->execute()) {
			return false;
		}
		return true;
		$connection->close();
	}
	
	function change_status($username, $new_status) {
		if($username === $_SESSION['language_server_user']) {
			return false;
		}
		
		if(($new_status > 2) || $new_status < 0) {
			return false;
		}
		
		$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
		if ($connection->connect_errno)
		{
			return false;
		}
		
		$status = status();
		
		if(!sufficient_status(status_username($username), $status)) {
			return false;
		}
		
		$command = "UPDATE accounts SET admin=? WHERE username=?;";
		
		if(!($stmt = $connection->prepare($command))) {
			return false;
		}
		if(!$stmt->bind_param('is', $new_status, $username)) {
			return false;
		}
		elseif (!$stmt->execute()) {
			return false;
		}
		return true;
		$connection->close();
	}
	
	function sufficient_status($target_status, $owner_status) {
		var_dump($target_status);
		var_dump($owner_status);
		return ($owner_status == 2) || ($target_status < $owner_status);
	}
	
	function delete_user($username) {
		if($username === $_SESSION['language_server_user']) {
			return false;
		}
		
		$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
		if ($connection->connect_errno)
		{
			return false;
		}
		
		$status = status();
		
		if(!sufficient_status(status_username($username), $status)) {
			return false;
		}
		
		$command = "DELETE FROM accounts WHERE username=?;";
		
		if(!($stmt = $connection->prepare($command))) {
			return false;
		}
		if(!$stmt->bind_param('s', $username)) {
			return false;
		}
		elseif (!$stmt->execute()) {
			return false;
		}
		return true;
		$connection->close();
	}
	
	function add_user($username, $first_name, $last_name) {
		$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
		if ($connection->connect_errno)
		{
			return false;
		}
		
		$status = status();
		
		$command = "INSERT INTO accounts (username, password, first_name, last_name, admin)  VALUES (?, ?, ?, ?, 0);";
		
		if(!($stmt = $connection->prepare($command))) {
			return false;
		}
		if(!$stmt->bind_param('ssss', $username, hash_password($username, null), $first_name, $last_name)) {
			return false;
		}
		elseif (!$stmt->execute()) {
			return false;
		}
		return true;
		$connection->close();
	}
?>