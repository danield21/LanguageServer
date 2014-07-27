<?php
	class Language {
		public $id;
		public $language;
		
		public function __construct($array = null) {
			$this->id = (isset($array['language_id'])) ? (int)$array['language_id'] : 0;
			$this->language = (isset($array['language'])) ? $array['language'] : '';
			return $this;
		}
		
		public function equals($other) {
			if(!isset($other) || !($other instanceof Language))
			{
				return false;
			}
			
			return $this->id == $other->id
				&& $this->language == $other->language;
		}
	
		public function is_valid() {
			return ($this->id !== 0)
				|| (trim($this->language) !== '');
		}
	}
?>
