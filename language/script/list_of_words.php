<?php
	header("Content-type: application/x-javascript");
	require_once '../php/all.php';
	$current_language = new Language;
	$current_category = new Category;
	$ct = new CategoriesTable(IP, USER, PASSWORD, DATABASE);
	$lt = new LanguagesTable(IP, USER, PASSWORD, DATABASE);
	$wt = new WordsTable(IP, USER, PASSWORD, DATABASE);
	if(isset($_GET['language_id'])) {
		$current_language = $lt->get_by_id($_GET['language_id']);
	}
	if(isset($_GET['category_id'])) {
		$current_category = $ct->get_by_id($_GET['category_id']);
	}
?>
function Word(id, word) {
	this.id = id;
	this.word = word;
}

function getListWords() {
	var array = new Array(
<?php
	$word_list = $wt->get_by_filter($current_category, $current_language);
	$count = count($word_list);
	for($i = 0; $i < $count; ++$i) {
?>
		new Word(<?php echo $word_list[$i]->id; ?>, "<?php echo $word_list[$i]->word; ?>")<?php
		if($i < $count - 1) {
			echo ",\n";
		} else {
			echo "\n";
		}
	}
?>
	);
	return array;
}
