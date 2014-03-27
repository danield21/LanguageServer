<?php require_once 'phpfunctions/AllFunctions.php'; ?>

function checkWord(id, word) {
	return word == <? (isset($_GET['id'])) ? get_by_word_id($_GET['id']) : null?>;
}