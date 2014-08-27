<?php
	class MysqlTable {
		protected $ip_;
		protected $user_;
		protected $pass_;
		protected $db_;
		
		/**
		 * Constructor: takes the connection info
		 * @param $ip - IP address the table is located on
		 * @param $user - The username of the account
		 * @param $pass - The password of the account
		 * @param $db - The database with the table
		**/
		public function __construct($ip, $user, $pass, $db) {
			$this->ip_ = $ip;
			$this->user_ = $user;
			$this->pass_ = $pass;
			$this->db_ = $db;
		}
	}
?>