<?php
	class AccountOptionsTable {
		private $ip_;
		private $user_;
		private $pass_;
		private $db_;
	
		public function __construct($ip, $user, $pass, $db) {
			$this->ip_ = $ip;
			$this->user_ = $user;
			$this->pass_ = $pass;
			$this->db_ = $db;
		}
		
		public function get_all() {
			$options = array();
			try {
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				
				$command = 'SELECT * FROM account_options ORDER BY min_admin_level, option_key';
				
				$stmt = $connect->prepare($command);
				$stmt->execute();
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				$options = array();
				
				foreach($results as $result) {
					$options[] = new AccountOption($result);
				}
			} catch(PDOException $ex) {
				log_info($ex->getMessage());
			}
			return $options;
		}
		
		public function get_min($admin) {
			$options = array();
			try {
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				
				$command = 'SELECT * FROM account_options WHERE min_admin_level <= :level ORDER BY min_admin_level, option_key';
				
				$stmt = $connect->prepare($command);
				$stmt->bindParam(':level', $admin, PDO::PARAM_INT);
				$stmt->execute();
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				foreach($results as $result) {
					$options[] = new AccountOption($result);
				}
			} catch(PDOException $ex) {
				log_info($ex->getMessage());
			}
			return $options;
		}
	}
?>
