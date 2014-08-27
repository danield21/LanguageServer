<?php
	class Setting {
		public $name;
		public $key;
		public $min_admin;
		
		public function __construct($array = null) {
			$this->name = (isset($array['name'])) ? (string)$array['name'] : '';
			$this->key = (isset($array['key'])) ? (string)$array['key'] : '';
			$this->min_admin = (isset($array['min'])) ? (int)$array['min'] : 0;
		}
		
		public function get_link($relativepath = '.') {
			return '<a href="' . $relativepath . '/?setting=' . urlencode($this->key) . '">' . $this->name . '&nbsp;Settings</a>';
		}
		
		public function filename() {
			return $this->key . '.php';
		}
		
		public function is_valid() {
			return $this->key !== '' && $this->name !== '';
		}
		
		public function toArray() {
			return ['name' => $this->name, 'key' => $this->key, 'min'=> $this->min_admin];
		}
	}
?>
