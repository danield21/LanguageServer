<?php
	class Word {
		public $id;
		public $word;
		public $primary_sound;
		public $secondary_sound;
		public $picture;
		public $language_id;
		
		public function __construct($array = null) {
			$this->id = (isset($array["word_id"])) ? (int)$array["word_id"] : 0;
			$this->word = (isset($array["word"])) ? $array["word"] : '';
			$this->primary_sound = (isset($array["primary_sound"])) ? $array["primary_sound"] : '';
			$this->secondary_sound = (isset($array["secondary_sound"])) ? $array["secondary_sound"] : '';
			$this->picture = (isset($array["picture_type"])) ? $array["picture_type"] : '';
			$this->language_id = (isset($array["language_id"])) ? (int)$array["language_id"] : '';
			return $this;
		}
		
		public function equals($other) {
			if(!isset($other) || !($other instanceof Word))
			{
				return false;
			}
			
			return $this->id == $other->id
					&& $this->word == $other->word
					&& $this->primary_sound == $other->primary_sound
					&& $this->secondary_sound == $other->secondary_sound
					&& $this->picture == $other->picture
					&& $this->language_id == $other->language_id;
		}
	
		public function is_valid() {
			return ($this->id !== 0)
				&& (trim($this->word) !== '')
				&& ($this->language_id !== 0);
		}
		
		public function pr_sound_file() {
			return $this->id . '.' . $this->primary_sound;
		}
		
		public function sec_sound_file() {
			return $this->id . '.' . $this->secondary_sound;
		}
		
		public function picture_file() {
			return $this->id . '.' .  $this->picture;
		}
	}
?>
