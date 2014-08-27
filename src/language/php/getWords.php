<?php
	require_once "all.php";
	class WordCat extends Word {
		public $categories;
		
		public function __construct($array = null) {
			parent::__construct($array);
			$this->categories = (isset($array["categories"])) ? $array["categories"] : [];
		}
	}
	
	$mysql = [new CategoriesTable(IP, USER, PASSWORD, DATABASE), new LanguagesTable(IP, USER, PASSWORD, DATABASE), new WordsTable(IP, USER, PASSWORD, DATABASE),];
	
	$words = $mysql[2]->get_all();
	
	$total = count($words);
	for($i = 0; $i < $total; ++$i) {
		$categories = $mysql[0]->get_by_word($words[$i], true);
		$words[$i] = $words[$i]->toArray();
		$words[$i]["categories"] = [];
		foreach($categories as $category) {
			$words[$i]["categories"][] = $category->id;
		}
		$words[$i] = new WordCat($words[$i]);
	}
	echo json_encode($words);
?>