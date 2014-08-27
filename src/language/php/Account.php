<?php
	class Account {
		public $id;
		private $user;
		public $first_name;
		public $last_name;
		private $admin;
		public $key;
		
		public function __construct($array = null, $key = 0) {
			$this->id = (isset($array["account_id"])) ? (int)$array["account_id"] : 0;
			$this->user = (isset($array["username"])) ? (string)$array["username"] : "";
			$this->first_name = (isset($array["first_name"])) ? (string)$array["first_name"] : "";
			$this->last_name = (isset($array["last_name"])) ? (string)$array["last_name"] : "";
			$this->admin = (isset($array["admin"])) ? (int)$array["admin"] : 0;
			$this->key = $key;
		}
		
		public function __get($member) {
			if(!isset($this->$member)) {
				trigger_error("Property Account::$member doesn't exists and cannot be set.", E_USER_ERROR);
			}
			return  $this->$member;
		}
		
		public function is_valid() {
			return ($this->id !== 0)
				|| (trim($this->user) !== '');
		}
	}
?>