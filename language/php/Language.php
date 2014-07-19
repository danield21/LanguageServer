<?php
	class Language {
		private $is_init_;
		private $id_;
		private $language_;
		
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
			$this->is_init_=true;
			$this->id_=(int)$array['language_id'];
			$this->language_=$array['language'];
		}
		
		public function init($id, $language) {
			$this->init_array(
				array(
					'language_id' => $id,
					'language' => $language,
				)
			);
		}
		
		public function is_init() {
			return $this->is_init_;
		}
		
		public function id() {
			return $this->id_;
		}
		
		public function language() {
			return $this->language_;
		}
		
		public function equals(Language $other)
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
				&& $this->language_ == $other->language_;
		}
		
		private function valid_array($array) {
			return isset($array['language_id'])
				&& isset($array['language']);
		}
	}
?>