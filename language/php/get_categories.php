<?php
require_once 'config.php';
require_once 'Category.php';

function get_all_categories() {
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		log_info("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
		return array();
	}
		
	$results = $connection->query('SELECT * FROM category;');
	$connection->close();
		
	$categories = array();
		
	for($i = 0; $i < $results->num_rows; $i++)
	{
		$results->data_seek($i);
		$categories[$i] = new Category;
		$result = $results->fetch_assoc();
		$categories[$i]->init_array($result);
	}
	return $categories;
}

function get_by_category_id($id) {
	$category = new Category;
	if(!isset($id)) {
		return $category;
	}
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		log_info("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
		return $category;
	}
	$command = 'SELECT * FROM category WHERE category_id = ? LIMIT 0, 1;';
	
	if(!($stmt = $connection->prepare($command)))
	{
		log_info("Prepare failed: (" . $connection->errno . ") " . $connection->error);
		return $category;
	}
	if(!$stmt->bind_param('s', $id))
	{
		log_info("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
		return $category;
	}
	elseif (!$stmt->execute())
	{
		log_info("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
		return $category;
	}
	$results = $stmt->get_result();
	$connection->close();
	
	$results->data_seek(0);
	$result = $results->fetch_assoc();
	$category->init_array($result);
	
	return $category;
}

function get_category_by_word(Word $word) {
	$categories = array();
	
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		log_info("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
		return $categories;
	}
	$command = 'SELECT * FROM is_in WHERE word_id = ?;';
	
	if(!($stmt = $connection->prepare($command)))
	{
		log_info("Prepare failed: (" . $connection->errno . ") " . $connection->error);
		return $categories;
	}
	if(!$stmt->bind_param('i', $word->id()))
	{
		log_info("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
		return $categories;
	}
	elseif (!$stmt->execute())
	{
		log_info("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
		return $categories;
	}
	
	$results = $stmt->get_result();
	$connection->close();
		
	for($i = 0; $i < $results->num_rows; $i++)
	{
		$results->data_seek($i);
		$categories[$i] = new Category;
		$result = $results->fetch_assoc();
		$categories[$i] = get_by_category_id($result['category_id']);
	}
	return $categories;
}

function get_all_subcategories(Category $current) {
	if(!isset($current) || !$current->is_init()) {
		return array();
	}
	
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		log_info("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
		return array();
	}
	
	$subcat = array( $current );
	$count = 1;
	
	$command = 'SELECT ' .
								'category.category_id AS category_id' .
								', category.category AS category ' .
								', category.description AS description' .
									' FROM category, is_subcategory_of' .
										' WHERE category.category_id = is_subcategory_of.subcategory_id' .
										' AND is_subcategory_of.parentcategory_id = ?;';

	if(!($stmt = $connection->prepare($command)))
	{
		 log_info("Prepare failed: (" . $connection->errno . ") " . $connection->error);
	}
	for($h = 0; $h < count($subcat); ++$h) {
		$id = $subcat[$h]->id();
		if(!$stmt->bind_param('s', $id))
		{
			log_info("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
		}
		elseif (!$stmt->execute())
		{
			log_info("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
		}
		$results = $stmt->get_result();
		$new_categories = array();
		$new_count = 0;
		for($i = 0; $i < $results->num_rows; $i++)
		{
			$results->data_seek($i);
			$new_categories[$i] = new Category;
			$result = $results->fetch_assoc();
			$new_categories[$i]->init_array($result);
			++$new_count;
		}
		foreach($new_categories as $new_cat) {
			$repeat = false;
			for($i = 0; $i < $count && !$repeat; ++$i) {
				if($new_cat->equals($subcat[$i])) {
					$repeat=true;
				}
			}
			if(!$repeat) {
				$subcat[] = $new_cat;
				++$count;
			}
		}
	}
	
	$stmt->close();
	
	$connection->close();
	return $subcat;
}

function get_direct_parent_categories(Category $current) {
	if(!isset($current) || !$current->is_init()) {
		return array();
	}
	
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		log_info("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
		return array();
	}
	
	$command = 'SELECT ' .
		'category.category_id AS category_id' .
		', category.category AS category ' .
		', category.description AS description' .
			' FROM category, is_subcategory_of' .
				' WHERE category.category_id = is_subcategory_of.parentcategory_id' .
				' AND is_subcategory_of.subcategory_id = ?;';

	if(!($stmt = $connection->prepare($command)))
	{
		log_info("Prepare failed: (" . $connection->errno . ") " . $connection->error);
	}
	if(!$stmt->bind_param('s', $current->id()))
	{
		log_info("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
	}
	elseif (!$stmt->execute())
	{
		log_info("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
	}
	$results = $stmt->get_result();
		
	$categories = array();
		
	for($i = 0; $i < $results->num_rows; $i++)
	{
		$results->data_seek($i);
		$categories[$i] = new Category;
		$result = $results->fetch_assoc();
		$categories[$i]->init_array($result);
	}
	
	$stmt->close();
	
	$connection->close();
	return $categories;
}

function get_all_parent_categories(Category $current) {
	if(!isset($current) || !$current->is_init()) {
		return array();
	}
	
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		log_info("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
		return array();
	}
	
	$parent_cat = array( $current );
	$count = 1;
	
	$command = 'SELECT ' .
								'category.category_id AS category_id' .
								', category.category AS category ' .
								', category.description AS description' .
									' FROM category, is_subcategory_of' .
										' WHERE category.category_id = is_subcategory_of.parentcategory_id' .
										' AND is_subcategory_of.subcategory_id = ?;';

	if(!($stmt = $connection->prepare($command)))
	{
		 log_info("Prepare failed: (" . $connection->errno . ") " . $connection->error);
	}
	for($h = 0; $h < count($parent_cat); ++$h) {
		if(!$stmt->bind_param('s', $parent_cat[$h]->id()))
		{
			log_info("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
		}
		elseif (!$stmt->execute())
		{
			log_info("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
		}
		$results = $stmt->get_result();
		$new_categories = array();
		$new_count = 0;
		for($i = 0; $i < $results->num_rows; $i++)
		{
			$results->data_seek($i);
			$new_categories[$i] = new Category;
			$result = $results->fetch_assoc();
			$new_categories[$i]->init_array($result);
			++$new_count;
		}
		foreach($new_categories as $new_cat) {
			$repeat = false;
			for($i = 0; $i < $count && !$repeat; ++$i) {
				if($new_cat->equals($parent_cat[$i])) {
					$repeat=true;
				}
			}
			if(!$repeat) {
				$parent_cat[] = $new_cat;
				++$count;
			}
		}
	}
	
	$stmt->close();
	
	$connection->close();
	return $parent_cat;
}

function delete_category($id) {
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		log_info("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
		return false;
	}
	$command = "DELETE FROM category WHERE category_id = ?";
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
	return true;
	$connection->close();
}

function add_category($category, $description) {
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		log_info("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
		return false;
	}
	$command = "INSERT INTO category (category, description) VALUES (?, ?)";
	
	if(!($stmt = $connection->prepare($command)))
	{
		log_info("Prepare failed: (" . $connection->errno . ") " . $connection->error);
		return false;
	}
	if(!$stmt->bind_param('ss', $category, $description))
	{
		log_info("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
		return false;
	}
	elseif (!$stmt->execute())
	{
		log_info("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
		return false;
	}
	return true;
	$connection->close();
}

function edit_category($id, $category, $description) {
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		log_info("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
		return false;
	}
	$command = "UPDATE category SET category=?, description=? WHERE category_id=?";
	
	if(!($stmt = $connection->prepare($command)))
	{
		log_info("Prepare failed: (" . $connection->errno . ") " . $connection->error);
		return false;
	}
	if(!$stmt->bind_param('ssi', $category, $description, $id))
	{
		log_info("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
		return false;
	}
	elseif (!$stmt->execute())
	{
		log_info("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
		return false;
	}
	return true;
	$connection->close();
}

function add_relationship($child_id, $parent_id) {
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		log_info("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
		return false;
	}
	$command = "INSERT INTO is_subcategory_of (subcategory_id, parentcategory_id) VALUES (?, ?)";
	
	if(!($stmt = $connection->prepare($command)))
	{
		log_info("Prepare failed: (" . $connection->errno . ") " . $connection->error);
		return false;
	}
	if(!$stmt->bind_param('ii', $child_id, $parent_id))
	{
		log_info("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
		return false;
	}
	elseif (!$stmt->execute())
	{
		log_info("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
		return false;
	}
	return true;
	$connection->close();
}

function delete_relationship($child_id, $parent_id) {
	$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
	if ($connection->connect_errno)
	{
		log_info("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
		return false;
	}
	$command = "DELETE FROM is_subcategory_of WHERE subcategory_id = ? && parentcategory_id = ?;";
	if(!($stmt = $connection->prepare($command)))
	{
		log_info("Prepare failed: (" . $connection->errno . ") " . $connection->error);
		return false;
	}
	if(!$stmt->bind_param('ii', $child_id, $parent_id))
	{
		log_info("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
		return false;
	}
	elseif (!$stmt->execute())
	{
		log_info("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
		return false;
	}
	return true;
	$connection->close();
}
?>