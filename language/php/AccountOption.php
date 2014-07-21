<?php
	class AccountOption {
		public $id;
		public $name;
		public $key;
		public $min_admin;
		
		public function __construct($array = null) {
			$this->id = (isset($array['option_id'])) ? $array['option_id'] : 0;
			$this->name = (isset($array['option_name'])) ? $array['option_name'] : '';
			$this->key = (isset($array['option_key'])) ? $array['option_key'] : '';
			$this->min_admin = (isset($array['min_admin_level'])) ? $array['min_admin_level'] : 0;
		}
		
		public function get_link($relativepath = '.') {
			return '<a href="' . $relativepath . '/?setting=' . urlencode($this->key) . '">' . $this->name . '</a>';
		}
	}
?>
