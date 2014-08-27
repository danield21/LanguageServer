<?php
	require_once 'Setting.php';
	class SettingsTable {
		private $xml_file_;
	
		public function __construct($xml_file) {
			$this->xml_file_ = $xml_file;
		}
		
		public function get_all() {	
			$xml = simplexml_load_file($this->xml_file_) or log_info("Unable to read XML file: " . $this->xml_file_);
			$settings = array();
			foreach($xml->setting as $setting) {
				$settings[] = new Setting([
						'name' => (string)$setting->title,
						'key' => (string)$setting['key'],
						'min' => (int)$setting['min'],
					]);
			}
			return $settings;
		}
		
		public function get_min($admin) {
			$xml = simplexml_load_file($this->xml_file_) or log_info("Unable to read XML file: " . $this->xml_file_);
			$settings = array();
			foreach($xml->setting as $setting) {
				if((int)$setting['min'] > $admin) continue;
				$settings[] = new Setting([
						'name' => (string)$setting->title,
						'key' => (string)$setting['key'],
						'min' => (int)$setting['min'],
					]);
			}
			return $settings;
		}
		
		public function get_by_key($key) {
			$xml = simplexml_load_file($this->xml_file_) or log_info("Unable to read XML file: " . $this->xml_file_);
			$settings = array();
			foreach($xml->setting as $setting) {
				$setting = new Setting([
						'name' => (string)$setting->title,
						'key' => (string)$setting['key'],
						'min' => (int)$setting['min'],
					]);
				if($setting->key === $key) {
					return $setting;
				}
			}
			return new Setting();
		}
	}
?>
