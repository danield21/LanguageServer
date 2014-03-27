<?php
	class Category {
		private $is_init_;
		private $id_;
		private $category_;
		private $description_;
		
		public function __constuct__() {
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
			$this->id_ = (int)$array['category_id'];
			$this->category_ = $array['category'];
			$this->description_ = $array['description'];
		}
		
		public function init($id, $category, $description) {
			$this->init_array(
				array(
					'category_id' => $id,
					'category' => $category,
					'description' => $description,
				)
			);
		}
		
		public function is_init() {
			return $this->is_init_;
		}
		
		public function id() {
			return $this->id_;
		}
		
		public function category() {
			return $this->category_;
		}
		
		public function description() {
			return $this->description_;
		}
		
		public function equals(Category $other)
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
				&& $this->category_ == $other->category_
				&& $this->description_ == $other->description_;
		}
		
		private function valid_array($array) {
			return isset($array['category_id'])
				&& isset($array['category'])
				&& isset($array['description']);
		}
	}
?>