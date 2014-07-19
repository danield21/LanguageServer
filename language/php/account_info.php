<?php
	/* hash_password will take the two words, and hash them together to create a key that is not easily found
	 * @param $username - ensures that each hash will be different even if they have the same password.
	 * @param $password - the key that the account will be accessed by
	 * @return (string) returns the hash of the input
	 */
	function hash_password($username, $password) {
		//if there is no password, then the hash will be of the username
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
	
	/* status will get the admin level of the user and return it
	 * @return (int) returns the admin level of current user
	 */
	function status() {
		//If the user id does not exist, then the admin level is 0
		if(!isset($_SESSION['language_server_rand_ID']) || !isset($_SESSION['language_server_' . $_SESSION['language_server_rand_ID']])) {
			return 0;
		}
		
		//Because the id was hidden using a rand function, we should get the value into a more readable var
		$id = $_SESSION['language_server_' . $_SESSION['language_server_rand_ID']];
		$user = $_SESSION['language_server_user'];
		
		//If the mysql command fails at any point, then the admin level is 0
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
	
	/* A more custom version of status
	 * @param $username is the username of the status we will want
	 * @return (int) returns the admin level of that specific user
	 */
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
	
	/* Gets all of the accounts in the database
	 * @return (array('username', 'first_name', last_name', ['admin'])) returns an array of all of the accounts
	 * @note if current user does not have admin, then an empty array is returned
	 * @note if current user is admin level 2, then they can see admin levels
	 */
	function get_all_accounts() {
		$status = status();
		$command = "";
		
		if($status) {
			if($status === 2) {
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
	
	/* Changes the password in the database
	 * @returns (bool) true if successful
	 * @note if the sufficent_status function fail, then this will fail
	 */
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
		
		if($status !== 2) {
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
	
	/* Changes the admin level of the user
	 * @param $username - target user
	 * @param $new_status - the status that the user will change to
	 * @returns (bool) true if successful
	 * @note if the sufficent_status function fail, then this will fail
	 */
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
		
		if($status !== 2) {
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
	
	/* Deletes user
	 * @returns (bool) true if successful
	 * @note if the sufficent_status function fail, then this will fail
	 */
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
		
		if($status !== 2) {
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
	
	/* Adds user
	 * @returns (bool) true if successful
	 */
	function add_user($username, $first_name, $last_name) {
		$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
		if ($connection->connect_errno)
		{
			return false;
		}
		
		$status = status();
		
		if($status !== 2) {
			return false;
		}
		
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
	
	function log_info($message) {
		$author = 'Unknown User';
		if(isset($_SESSION['language_server_user'])) {
			$author = $_SESSION['language_server_user'];
		}
		
		$time = date("l jS \of F Y h:i:s A");
		
		$entry = $time . "\nUser: " . $author . "\nAction: " . $message . "\n\n";
		
		return true === file_put_contents('../logs.txt', $entry, FILE_APPEND | LOCK_EX);
	}
?>