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
		
		//If PDO fails at any point, then the admin level is 0
		try {
			$connect = new PDO('mysql:host=' . IP . '; dbname=' . DATABASE . ';charset=utf8', USER, PASSWORD);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	
			$command = 'SELECT admin FROM accounts WHERE username = :user && account_id = :id LIMIT 0, 1;';
	
			$stmt = $connect->prepare($command);
			$stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
			$stmt->bindValue(':user', $user, PDO::PARAM_STR);
			$stmt->execute();
	
			
			$result = $stmt->fetchAll();
			
			return (isset($result[0])) ? $result[0]['admin'] : 0;
		} catch(PDOException $ex) {
			log_info($ex->getMessage());
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
		
		//If PDO fails at any point, then the admin level is 0
		$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
		if ($connection->connect_errno)
		try {
			$connect = new PDO('mysql:host=' . IP . '; dbname=' . DATABASE . ';charset=utf8', USER, PASSWORD);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	
			$command = 'SELECT admin FROM accounts WHERE username = :user LIMIT 0, 1;';
	
			$stmt = $connect->prepare($command);
			$stmt->bindValue(':user', $user, PDO::PARAM_STR);
			$stmt->execute();
	
			
			$result = $stmt->fetchAll();
			
			return (isset($result[0])) ? $result['admin'] : 0;
		} catch(PDOException $ex) {
			log_info($ex->getMessage());
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
		$users = array();
		$command = "";
		
		if($status) {
			$command = ($status === 2) ?
				"SELECT username, first_name, last_name, admin FROM accounts;" :
				"SELECT username, first_name, last_name FROM accounts;";
		} else {
			return $users;
		}
		try {
			$connect = new PDO('mysql:host=' . IP . '; dbname=' . DATABASE . ';charset=utf8', USER, PASSWORD);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			
			$stmt = $connect->prepare($command);
			$stmt->execute();
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			//Clean the array of any PDO stuff
			foreach($results as $row){
				$users[] = $row;
			}
		
			return $users;
		} catch(PDOException $ex) {
			log_info($ex->getMessage());
			return $users;
		}
	}
	
	/* Changes the password in the database
	 * @returns (bool) true if successful
	 */
	function change_password($username, $password, $comfirm_pass) {
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
		
		try {
			$connect = new PDO('mysql:host=' . IP . '; dbname=' . DATABASE . ';charset=utf8', USER, PASSWORD);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		
			$command = "UPDATE accounts SET password=:pass WHERE username=:user;";
			
			$stmt = $connect->prepare($command);
			$stmt->bindValue(':user', $username, PDO::PARAM_STR);
			$stmt->bindValue(':pass', hash_password($username, $password), PDO::PARAM_STR);
			$stmt->execute();
			
			return true;
		} catch(PDOException $ex) {
			log_info($ex->getMessage());
			return false;
		}
	}
	
	/* Changes the admin level of the user
	 * @param $username - target user
	 * @param $new_status - the status that the user will change to
	 * @returns (bool) true if successful
	 */
	function change_status($username, $new_status) {
		if($username === $_SESSION['language_server_user']) {
			return false;
		}
		
		if(($new_status > 2) || $new_status < 0) {
			return false;
		}
		
		if(status() !== 2) {
			return false;
		}
		
		try {
			$connect = new PDO('mysql:host=' . IP . '; dbname=' . DATABASE . ';charset=utf8', USER, PASSWORD);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		
			$command = "UPDATE accounts SET admin=:admin WHERE username=:user;";
			
			$stmt = $connect->prepare($command);
			$stmt->bindValue(':user', $username, PDO::PARAM_STR);
			$stmt->bindValue(':admin', $new_status, PDO::PARAM_INT);
			$stmt->execute();
			
			return true;
		} catch(PDOException $ex) {
			log_info($ex->getMessage());
			return false;
		}
	}
	
	/* Deletes user
	 * @returns (bool) true if successful
	 */
	function delete_user($username) {
		if($username === $_SESSION['language_server_user']) {
			return false;
		}
		
		if(status() !== 2) {
			return false;
		}
		
		try {
			$connect = new PDO('mysql:host=' . IP . '; dbname=' . DATABASE . ';charset=utf8', USER, PASSWORD);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			
			$command = "DELETE FROM accounts WHERE username=:user;";
			
			$stmt = $connect->prepare($command);
			$stmt->bindValue(':user', $username, PDO::PARAM_STR);
			$stmt->execute();
			
			return true;
		} catch(PDOException $ex) {
			log_info($ex->getMessage());
			return false;
		}
	}
	
	/* Adds user
	 * @returns (bool) true if successful
	 */
	function add_user($username, $first_name, $last_name) {
		if(status() !== 2) {
			return false;
		}
		
		try {
			$connect = new PDO('mysql:host=' . IP . '; dbname=' . DATABASE . ';charset=utf8', USER, PASSWORD);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			
			$command = "INSERT INTO accounts (username, password, first_name, last_name, admin)  VALUES (:user, :pass, :first, :last, 0);";
			
			$stmt = $connect->prepare($command);
			$stmt->bindValue(':user', $username, PDO::PARAM_STR);
			$stmt->bindValue(':pass', hash_password($username, null), PDO::PARAM_STR);
			$stmt->bindValue(':first', $first_name, PDO::PARAM_STR);
			$stmt->bindValue(':last', $last_name, PDO::PARAM_STR);
			$stmt->execute();
			
			return true;
		} catch(PDOException $ex) {
			log_info($ex->getMessage());
			return false;
		}
	}
	
	function log_info($message) {
		$author = (isset($_SESSION['language_server_user'])) ? $_SESSION['language_server_user'] : 'Unknown User';
		
		$time = date("l jS \of F Y h:i:s A");
		
		$entry = $time . "\nUser: " . $author . "\nAction: " . $message . "\n\n";
		echo $entry;
		return true === file_put_contents('../logs.txt', $entry, FILE_APPEND | LOCK_EX);
	}
?>
