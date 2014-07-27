<?php
	/**
	 * @author Daniel J Dominguez
	 * @version 2.0
	 * This class contains information about the categories
	 * and allows conversion from a mysql array.
	 **/
	class Category {

		public $id;
		public $category;
		public $description;
		
		/**
		 * Constructor: Takes an array and set the member vars to the following values
		 * @param $array - Array should contain the keys for 'category_id', 'category', or description.
		 *		If keys are not present, then id will be 0 and category and description will be 0.
		 *		All other values will be ignored.
		 **/
		public function __construct($array = null) {
			$this->id = (isset($array['category_id'])) ? (int)$array['category_id'] : 0;
			$this->category = (isset($array['category'])) ? $array['category'] : '';
			$this->description = (isset($array['description'])) ? $array['description'] : '';
			return $this;
		}
		
		/**
		 * Checks if the two objects are equal
		 * @return true if they are exactly the same
		 **/
		public function equals($other) {
			if(!isset($other) || !($other instanceof Category))
			{
				return false;
			}
			
			return $this->id === $other->id
				&& $this->category === $other->category
				&& $this->description === $other->description;
		}
		
		/**
		 * Checks if the current instance is in the array
		 * @return true if it is the array
		**/
		public function in_array($array) {
			if(!is_array($array)) {
				return false;
			}
			foreach($array as $item) {
				if($this->equals($item)) {
					return true;
				}
			}
			return false;
		}
		
		/**
		 * Checks to see if the instance is filled
		 * @return true if neither id or category are their default values
		 **/	
		public function is_valid() {
			return ($this->id !== 0)
				&& (trim($this->category) !== '');
		}
	}
?>
