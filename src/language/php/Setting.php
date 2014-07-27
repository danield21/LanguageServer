<?php
	class Setting {
		public $name;
		public $key;
		public $min_admin;
		
		public function __construct($xml_object = null) {
			$this->name = (isset($xml_object->title)) ? (string)$xml_object->title : '';
			$this->key = (isset($xml_object['key'])) ? (string)$xml_object['key'] : '';
			$this->min_admin = (isset($xml_object['min'])) ? (int)$xml_object['min'] : 0;
		}
		
		public function get_link($relativepath = '.') {
			return '<a href="' . $relativepath . '/?setting=' . urlencode($this->key) . '">' . $this->name . '&nbsp;Settings</a>';
		}
		
		public function filename() {
			return $this->key . '.php';
		}
	}
?>
