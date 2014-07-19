<?php
require_once 'config.php';
require_once 'Language.php';

function get_all_languages() {
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		log_info("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
		return array();
	}
		
	$results = $connection->query('SELECT * FROM language;');
	$connection->close();
		
	$languages = array();
		
	for($i = 0; $i < $results->num_rows; $i++)
	{
		$results->data_seek($i);
		$languages[$i] = new Language;
		$result = $results->fetch_assoc();
		$languages[$i]->init_array($result);
	}
	return $languages;
}

function get_by_language_id($id) {
	$language = new Language;
	if(!isset($id)) {
		return $language;
	}
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		log_info("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
		return $language;
	}
	$command = 'SELECT * FROM language WHERE language_id = ? LIMIT 0, 1;';
	
	if(!($stmt = $connection->prepare($command)))
	{
		log_info("Prepare failed: (" . $connection->errno . ") " . $connection->error);
		return $language;
	}
	if(!$stmt->bind_param('s', $id))
	{
		log_info("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
		return $language;
	}
	elseif (!$stmt->execute())
	{
		log_info("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
		return $language;
	}
	$results = $stmt->get_result();
	$connection->close();
	
	$results->data_seek(0);
	$result = $results->fetch_assoc();
	$language->init_array($result);
	
	return $language;
}

function delete_language($id) {
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		log_info("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
		return false;
	}
	$command = "DELETE FROM language WHERE language_id = ?";
	if(!($stmt = $connection->prepare($command)))
	{
		log_info("Prepare failed: (" . $connection->errno . ") " . $connection->error);
		return false;
	}
	if(!$stmt->bind_param('s', $id))
	{
		log_info("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
		return false;
	}
	elseif (!$stmt->execute())
	{
		log_info("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
		return false;
	}
	log_info("Deleted language with id of " . $id);
	$connection->close();
	return true;
}

function add_language($language) {
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		log_info("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
		return false;
	}
	$command = "INSERT INTO language (language) VALUES (?)";
	
	if(!($stmt = $connection->prepare($command)))
	{
		log_info("Prepare failed: (" . $connection->errno . ") " . $connection->error);
		return false;
	}
	if(!$stmt->bind_param('s', $language))
	{
		log_info("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
		return false;
	}
	elseif (!$stmt->execute())
	{
		log_info("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
		return false;
	}
	log_info("Added " . $language . " with id of " . $connection->insert_id);
	$connection->close();
	return true;
}

function edit_language($id, $language) {
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		log_info("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
		return false;
	}
	$command = "UPDATE language SET language=? WHERE language_id=?";
	
	if(!($stmt = $connection->prepare($command)))
	{
		log_info("Prepare failed: (" . $connection->errno . ") " . $connection->error);
		return false;
	}
	if(!$stmt->bind_param('si', $language, $id))
	{
		log_info("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
		return false;
	}
	elseif (!$stmt->execute())
	{
		log_info("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
		return false;
	}
	$connection->close();
	log_info("Changed language with id of " . $id . " to " . $language);
	return true;
}
?>