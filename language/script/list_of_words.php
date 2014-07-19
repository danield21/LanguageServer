<?php
	header("Content-type: application/x-javascript");
	require_once 'php/all.php';
	$current_language = new Language;
	$current_category = new Category;
	if(isset($_GET['language_id'])) {
		$current_language = get_by_language_id($_GET['language_id']);
	}
	if(isset($_GET['category_id'])) {
		$current_category = get_by_category_id($_GET['category_id']);
	}
?>
function word(id, word) {
	var obj = new Object();
	obj.id = id;
	obj.word = word;
	return obj;
}

function getListWords() {
	var array = new Array(
<?php
	$word_list = get_words($current_category, $current_language);
	$count = count($word_list);
	for($i = 0; $i < $count; ++$i) {
?>
		word(<?php echo $word_list[$i]->id(); ?>, "<?php echo $word_list[$i]->word(); ?>")<?php
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