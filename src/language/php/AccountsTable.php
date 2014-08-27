<?php
	require_once 'MysqlTable.php';
	require_once 'Account.php';
	
	class AccountsTable extends MysqlTable{
		
		public function get_all() {
			try {
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				
				$command = "SELECT * FROM accounts";
				
				$stmt = $connect->prepare($command);
				$stmt->execute();
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				$accounts = [];
				
				foreach($results as $row){
					$accounts[] = new Account($row);
				}
				
				return $accounts;
			} catch(PDOException $ex) {
				log_info($ex->getMessage());
				return [];
			}
		}
		
		public function get_by_id($id, $key = 0) {
			$account = new Account;
			if(!isset($id)) {
				return $account;
			}
			try {
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	
				$command = 'SELECT * FROM accounts WHERE account_id = :id LIMIT 0, 1;';
	
				$stmt = $connect->prepare($command);
				$stmt->bindValue(':id', (int)$id, PDO::PARAM_STR);
				$stmt->execute();
				
				$result = $stmt->fetchAll();
				if(isset($result[0]))
				{
					$account = new Account($result[0], $key);
				}
			} catch(PDOException $ex) {
				log_info($ex->getMessage());
			}
			return $account;
		}
		
		/* 
		 * hash_password will take the two words, and hash them together to create a key that is not easily found
		 * @param $username - ensures that each hash will be different even if they have the same password.
		 * @param $password - the key that the account will be accessed by
		 * @return (string) returns the hash of the input
		 */
		public function hash_password($username, $password) {
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
		
		public function authorize($username, $password) {
			$id = 0;
			try {
				$hash = $this->hash_password($username, $password);
				
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	
				$command = 'SELECT account_id FROM accounts WHERE username = :username AND password = :password LIMIT 0, 1;';
	
				$stmt = $connect->prepare($command);
				$stmt->bindValue(':username', $username, PDO::PARAM_STR);
				$stmt->bindValue(':password', $hash, PDO::PARAM_STR);
				$stmt->execute();
				
				$result = $stmt->fetchAll();
				
				$id = (isset($result[0]["account_id"])) ? $result[0]["account_id"] : 0;
			} catch(PDOException $ex) {
				log_info($ex->getMessage());
			}
			return $id;
		}
		
		public function add(Account $account, $password) {
			if(!$account->is_valid()) {
				return false;
			}
			try {
				$hash = $this->hash_password($account->user, $password);
				
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	
				$command = 'INSERT INTO accounts (username, password, first_name, last_name, admin) VALUES (:username, :password, :firstname, :lastname, :admin);';
	
				$stmt = $connect->prepare($command);
				$stmt->bindValue(':username', $account->user, PDO::PARAM_STR);
				$stmt->bindValue(':password', $hash, PDO::PARAM_STR);
				$stmt->bindValue(':firstname', $account->first_name, PDO::PARAM_STR);
				$stmt->bindValue(':lastname', $account->last_name, PDO::PARAM_STR);
				$stmt->bindValue(':admin', $account->admin, PDO::PARAM_STR);
				$stmt->execute();
				
				$account->id = (int)$connect->lastInsertId();
				log_info('Added account: ' . $account->user . ' to table with id: ' . $account->id);
				return true;
			} catch(PDOException $ex) {
				log_info($ex->getMessage());
				return false;
			}
		}
		
		public function edit(Account $account) {
			try {
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				
				$command = 'UPDATE accounts SET username = :username, first_name = :firstname, last_name = :lastname, admin = :admin WHERE account_id = :id;';
				
				$stmt = $connect->prepare($command);
				$stmt->bindValue(':id', (int)$account->id, PDO::PARAM_INT);
				$stmt->bindValue(':username', $account->user, PDO::PARAM_STR);
				$stmt->bindValue(':firstname', $account->first_name, PDO::PARAM_STR);
				$stmt->bindValue(':lastname', $account->last_name, PDO::PARAM_STR);
				$stmt->bindValue(':admin', $account->admin, PDO::PARAM_STR);
				$stmt->execute();
				
				log_info("Changed Account with id of " . $account->id . " to " . $account->user);
				return true;
			} catch(PDOException $ex) {
				log_info($ex->getMessage());
				return false;
			}
		}
		
		public function delete(Account $account) {
			try {
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				
				$command = 'DELETE FROM accounts WHERE account_id = :id';
				
				$stmt = $connect->prepare($command);
				$stmt->bindValue(':id', (int)$account->id, PDO::PARAM_INT);
				$stmt->execute();
				
				log_info('Deleted Account: ' . $account->user . ' from table with id: ' . $account->id);
				return true;
			} catch(PDOException $ex) {
				log_info($ex->getMessage());
				return false;
			}
		}
		
		public function change_password(Account $account, $password) {
			try {
				$hash = $this->hash_password($account->user, $password);
				
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				
				$command = 'UPDATE accounts SET password = :password WHERE account_id = :id;';
				
				$stmt = $connect->prepare($command);
				$stmt->bindValue(':id', (int)$account->id, PDO::PARAM_INT);
				$stmt->bindValue(':password', $hash, PDO::PARAM_STR);
				$stmt->execute();
				
				log_info("Changed Account with id of " . $account->id . " to " . $account->user);
				return true;
			} catch(PDOException $ex) {
				log_info($ex->getMessage());
				return false;
			}
			
		}
	}
?>