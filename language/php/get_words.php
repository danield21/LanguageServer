<?php
require_once 'config.php';
require_once 'Word.php';
require_once 'Language.php';
require_once 'Category.php';

function get_all_words() {
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		return array();
	}
	$results = $connection->query('SELECT * FROM word;');
	$connection->close();
		
	$words = array();
	
	for($i = 0; $i < $results->num_rows; ++$i)
	{
		$results->data_seek($i);
		$words[$i] = new Word;
		$result = $results->fetch_assoc();
		$words[$i]->init_array($result);
	}
	return $words;
}

function get_words(Category $category, Language $language) {
	$words = array();
	$command = 'SELECT ' .
		'word.word_id AS word_id' .
		', word.word AS word' .
		', word.primary_sound AS primary_sound' .
		', word.secondary_sound AS secondary_sound' .
		', word.picture_type AS picture_type' .
		', word.language_id AS language_id' .
		' FROM word' .
		' NATURAL JOIN is_in';
	$previous = false;
	if($language->is_init()) {
		$previous = true;
		$command .= ' WHERE language_id = ?';
	}
	if($category->is_init()) {
		$categories = get_all_subcategories($category);
		foreach($categories as $cat) {
			if($previous) {
				$command .= ' AND category_id = ';
			}
			else {
				$command .= ' WHERE category_id = ';
			}
			$command .= $cat->id();
			$previous = true;
		}
	}
	$command .= ';';
	
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		return $words;
	}
	if($language->is_init()) {
		if(!($stmt = $connection->prepare($command)))
		{
			 echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;
			return $words;
		}
		if ($connection->connect_errno) {
			return $words;
		}
		$id = $language->id();
		if(!$stmt->bind_param('i', $id))
		{
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			return $words;
		}
		if (!$stmt->execute())
		{
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			return $words;
		}
		$results = $stmt->get_result();
	}
	else {
		$results = $connection->query($command);
	}
	$connection->close();
	
	for($i = 0; $i < $results->num_rows; $i++) {
		$results->data_seek($i);
		$words[$i] = new Word();
		$words[$i]->init_array($results->fetch_assoc());
	}
	return $words;
}

function get_by_word_id($id) {
	$word = new Word;
	if(!isset($id)) {
		return $word;
	}
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		return $word;
	}
	$command = 'SELECT * FROM word WHERE word_id = ? LIMIT 0, 1;';
	
	if(!($stmt = $connection->prepare($command)))
	{
		echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;
		return $word;
	}
	if(!$stmt->bind_param('i', $id))
	{
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		return $word;
	}
	elseif (!$stmt->execute())
	{
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		return $word;
	}
	$results = $stmt->get_result();
	$connection->close();
	
	$results->data_seek(0);
	$result = $results->fetch_assoc();
	$word->init_array($result);
	
	return $word;
}

function delete_word($id) {
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		return false;
	}
	$command = "DELETE FROM word WHERE word_id = ?";
	if(!($stmt = $connection->prepare($command)))
	{
		echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;
		return false;
	}
	if(!$stmt->bind_param('s', $id))
	{
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		return false;
	}
	elseif (!$stmt->execute())
	{
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		return false;
	}
	return true;
	$connection->close();
}

function add_word($word, $primary_ex, $secondary_ex, $pic_ex, $language_id) {
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		return false;
	}
	$command = "INSERT INTO word (word, primary_sound, secondary_sound, picture_type, language_id) VALUES (?, ?, ?, ?, ?)";
	
	if(!($stmt = $connection->prepare($command)))
	{
		echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;
		return false;
	}
	if(!$stmt->bind_param('sssss', $word, $primary_ex, $secondary_ex, $pic_ex, $language_id))
	{
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		return false;
	}
	elseif (!$stmt->execute())
	{
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		return false;
	}
	$id = $connection->insert_id();
	$connection->close();
	return $id;
}

function edit_word($id, $word, $primary_ex, $secondary_ex, $pic_ex, $language_id) {
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		return false;
	}
	$command = "UPDATE category SET word=?, primary_sound=?, secondary_sound=?, picture_type=?, language_id=? WHERE word_id=?";
	
	if(!($stmt = $connection->prepare($command)))
	{
		echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;
		return false;
	}
	if(!$stmt->bind_param('sssssi', $word, $primary_ex, $secondary_ex, $pic_ex, $language_id, $id))
	{
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		return false;
	}
	elseif (!$stmt->execute())
	{
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		return false;
	}
	return true;
	$connection->close();
}
?>