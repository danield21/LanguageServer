<?php
	class Category {

		public $id;
		public $category;
		public $description;
		
		public function __construct($array = null) {
			$this->id = (isset($array['category_id'])) ? (int)$array['category_id'] : 0;
			$this->category = (isset($array['category'])) ? $array['category'] : '';
			$this->description = (isset($array['description'])) ? $array['description'] : '';
			return $this;
		}
	
		public function equals($other) {
			if(!isset($other) || !($other instanceof Category))
			{
				return false;
			}
			
			return $this->id == $other->id
				&& $this->category == $other->category
				&& $this->description == $other->description;
		}
	
		public function is_valid() {
			return ($this->id !== 0)
				|| (trim($this->category) !== '');
		}
	}
?>
