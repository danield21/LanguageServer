<?php
	require_once "all.php";
	$data = [];
	if(isset($_POST['type']) && isset($_POST['filter'])) {
		if($_POST['type'] === 'categories') {
			$table = new CategoriesTable(IP, USER, PASSWORD, DATABASE);
			switch ($_POST['filter']) {
				case "all":
					$data = $table->get_all();
					break;
				case "id":
					if(isset($_POST['args'][0])) { 
						$data = $table->get_by_id($_POST['args'][0]);
					}
					break;
				case "word":
					if(isset($_POST['args'][0])) {
						$word = new Word(["word_id"=>$_POST['args'][0]]);
						$data = $table->get_by_word($word);
					}
					break;
				case "direct_children":
					if(isset($_POST['args'][0])) {
						$category = new Category(["category_id"=>$_POST['args'][0]]);
						$data = $table->get_direct_children($category);
					}
					break;
				case "all_children":
					if(isset($_POST['args'][0])) {
						$category = new Category(["category_id"=>$_POST['args'][0]]);
						$data = $table->get_all_children($category);
					}
					break;
				case "direct_parents":
					if(isset($_POST['args'][0])) {
						$category = new Category(["category_id"=>$_POST['args'][0]]);
						$data = $table->get_direct_parents($category);
					}
					break;
				case "all_parents":
					if(isset($_POST['args'][0])) {
						$category = new Category(["category_id"=>$_POST['args'][0]]);
						$data = $table->get_all_parents($category);
					}
					break;
			}
		} else if($_POST['type'] === 'languages') {
			$table = new LanguagesTable(IP, USER, PASSWORD, DATABASE);
			switch ($_POST['filter']) {
				case "all":
					$data = $table->get_all();
					break;
				case "id":
					if(isset($_POST['args'][0])) { 
						$data = $table->get_by_id($_POST['args'][0]);
					}
					break;
			}
		} else if($_POST['type'] === 'words') {
			$table = new WordsTable(IP, USER, PASSWORD, DATABASE);
			switch ($_POST['filter']) {
				case "all":
					$data = $table->get_all();
					break;
				case "id":
					if(isset($_POST['args'][0])) { 
						$data = $table->get_by_id($_POST['args'][0]);
					}
					break;
				case "language":
					if(isset($_POST['args'][0])) {
						$language = new Language(["language_id"=>$_POST['args'][0]]);
						$data = $table->get_by_language($language);
					}
					break;
				case "category":
					if(isset($_POST['args'][0])) {
						$category = new Category(["category_id"=>$_POST['args'][0]]);
						$data = $table->get_by_category($category);
					}
					break;
				case "both":
					if(isset($_POST['args'][0]) && isset($_POST['args'][1])) {
						$category = new Category(["category_id"=>$_POST['args'][0]]);
						$language = new Language(["language_id"=>$_POST['args'][1]]);
						$data = $table->get_by_filter($category, $language);
					}
					break;
				case "no_category":
						$data = $table->get_no_category();
					break;
				case "ncat_ylan":
						$language = new Language(["language_id"=>$_POST['args'][0]]);
						$data = $table->get_no_category_but_language($language);
					break;
			}
		}
	}
	echo json_encode($data);
?>