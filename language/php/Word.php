<?php
	class Word {
		private $is_init_;
		private $id_;
		private $word_;
		private $primary_sound_;
		private $secondary_sound_;
		private $picture_;
		private $language_id_;
	
		public function __construct__() {
			$this->is_init_ = false;
		}
				
		public function init_array($array) {
			if(!isset($array)) {
				$this->is_init_ = false;
				return;
			}
			if(!$this->valid_array($array)) {
				$this->is_init_ = false;
				return;
			}
			$this->is_init_ = true;
			$this->id_ = (int)$array["word_id"];
			$this->word_ = $array["word"];
			$this->primary_sound_ = $array["primary_sound"];
			$this->secondary_sound_ = $array["secondary_sound"];
			$this->picture_ = $array["picture_type"];
			$this->language_id_ = $array["language_id"];
		}
				
		public function init($id, $word, $primary_sound, $secondary_sound, $picture, $language_id) {			$array = 
			$this->init_array(
				array(
					"word_id" => $id,
					"word" => $word,
					"primary_sound" => $primary_sound,
					"secondary_sound" => $secondary_sound,
					"picture_type" => $picture,
					"language_id" => $language_id,
				)
			);
		}
		
		public function is_init() {
			return $this->is_init_;
		}
		
		public function id() {
			return $this->id_;
		}
		
		public function word() {
			return $this->word_;
		}
		
		public function primary_sound() {
			return $this->primary_sound_;
		}
		
		public function secondary_sound() {
			return $this->secondary_sound_;
		}
		
		public function picture() {
			return $this->picture_;
		}
		
		public function language_id() {
			return $this->language_id_;
		}
		
		public function equals(Word $other)
		{
			if(!isset($other))
			{
				return false;
			}
			if($this->is_init_ != $other->is_init_)
			{
				return false;
			}
			if(!$this->is_init_)
			{
				return true;
			}
			return $this->id_ == $other->id_
					&& $this->word_ == $other->word_
					&& $this->sound_ == $other->sound_
					&& $this->picture_ == $other->picture_
					&& $this->language_id_ == $other->language_id_;
		}
		
		private function valid_array($array) {
			return isset($array['word_id'])
					&& isset($array['word'])
					&& isset($array['primary_sound'])
					&& isset($array['secondary_sound'])
					&& isset($array['picture_type'])
					&& isset($array['language_id']);
		}
	}
?>