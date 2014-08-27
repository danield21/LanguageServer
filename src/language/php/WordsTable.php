<?php
	require_once 'MysqlTable.php';
	require_once 'Word.php';
	require_once 'Language.php';
	require_once 'CategoriesTable.php';

	class WordsTable extends MysqlTable {
		
		public function get_all() {
			try {
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				
				$command = 'SELECT * FROM word;';
				
				$stmt = $connect->prepare($command);
				$stmt->execute();
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				$words = array();
				
				foreach($results as $row){
					$words[] = new Word($row);
				}
				
				return $words;
			} catch(PDOException $ex) {
				log_info($ex->getMessage());
				return array();
			}
		}
		
		public function get_by_id($id) {
			$word = new Word;
			if(!isset($id)) {
				return $word;
			}
			try {
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	
				$command = 'SELECT * FROM word WHERE word_id = :id LIMIT 0, 1;';
	
				$stmt = $connect->prepare($command);
				$stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
				$stmt->execute();
				
				$result = $stmt->fetchAll();
				
				if(isset($result[0]))
				{
					$word = new Word($result[0]);
				}
			} catch(PDOException $ex) {
				log_info($ex->getMessage());
			}
			return $word;
		}
		
		public function get_by_language(Language $language) {
			return $this->get_by_filter(new Category, $language);
		}
		
		public function get_by_category(Category $category) {
			return $this->get_by_filter($category, new Language);
		}

		public function get_by_filter(Category $category, Language $language) {
			$ct = new CategoriesTable($this->ip_, $this->user_, $this->pass_, $this->db_);
			
			$was_given = $category->is_valid();
			
			//Cleans and validates the given category
			$category->id = (int)$category->id;
			
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
			if($language->is_valid()) {
				$previous = true;
				$command .= ' WHERE language_id = :langID';
			}
			if($category->is_valid()) {
				$command .= (!$previous) ? ' WHERE category_id = ' : ' AND (category_id = ';
				$command .= $category->id;
				
				$categories = $ct->get_all_children($category);
				foreach($categories as $cat) {
					$command .= ' OR category_id = ' . $cat->id;
				}
				if($previous) {
					$command .= ')';
				}
			}
			$command .= ';';
			try {
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				
				$stmt = $connect->prepare($command);
				$stmt->bindValue(':langID', (int)$language->id, PDO::PARAM_INT);
				$stmt->execute();
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				$words = array();
				
				foreach($results as $row){
					$words[] = new Word($row);
				}
				
				return $words;
			} catch(PDOException $ex) {
				log_info($ex->getMessage() . "\n" . $command);
				return array();
			}
		}
		
		public function get_no_category() {
			try {
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				
				$command = 'SELECT * FROM word WHERE word_id NOT IN (SELECT word_id FROM is_in)';
				
				$stmt = $connect->prepare($command);
				$stmt->execute();
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				$words = array();
				
				foreach($results as $row){
					$words[] = new Word($row);
				}
				
				return $words;
			} catch(PDOException $ex) {
				log_info($ex->getMessage());
				return array();
			}
		}
		
		public function get_no_category_but_language(Language $language) {
			try {
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				
				$command = 'SELECT * FROM word WHERE word_id NOT IN (SELECT word_id FROM is_in) AND language_id = :langID';
				
				$stmt = $connect->prepare($command);
				$stmt->bindValue(':langID', (int)$language->id, PDO::PARAM_INT);
				$stmt->execute();
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				$words = array();
				
				foreach($results as $row){
					$words[] = new Word($row);
				}
				
				return $words;
			} catch(PDOException $ex) {
				log_info($ex->getMessage());
				return array();
			}
		}

		public function add(Word $add) {
			try {
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				
				$command = "INSERT INTO word (word, primary_sound, secondary_sound, picture_type, language_id) VALUES (:word, :ps, :ss, :pt, :langID)";
	
				$stmt = $connect->prepare($command);
				$stmt->bindValue(':word', $add->word, PDO::PARAM_STR);
				$stmt->bindValue(':ps', $add->primary_sound, PDO::PARAM_STR);
				$stmt->bindValue(':ss', $add->secondary_sound, PDO::PARAM_STR);
				$stmt->bindValue(':pt', $add->picture, PDO::PARAM_STR);
				$stmt->bindValue(':langID', (int)$add->language_id, PDO::PARAM_INT);
				$stmt->execute();
				
				$add->id = (int)$connect->lastInsertId();
				log_info('Added word: ' . $add->word . ' to table with id: ' . $add->id);
				return true;
			} catch(PDOException $ex) {
				log_info($ex->getMessage());
				return false;
			}
		}

		public function edit(Word $edit) {
			try {
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				
				$command = "UPDATE word SET word=:word, primary_sound=:ps, secondary_sound=:ss, picture_type=:pt, language_id=:lanID WHERE word_id=:id";
	
				$stmt = $connect->prepare($command);
				$stmt->bindValue(':id', $edit->id, PDO::PARAM_STR);
				$stmt->bindValue(':word', $edit->word, PDO::PARAM_STR);
				$stmt->bindValue(':ps', $edit->primary_sound, PDO::PARAM_STR);
				$stmt->bindValue(':ss', $edit->secondary_sound, PDO::PARAM_STR);
				$stmt->bindValue(':pt', $edit->picture, PDO::PARAM_STR);
				$stmt->bindValue(':lanID', (int)$edit->language_id, PDO::PARAM_INT);
				$stmt->execute();
				
				$add->id = (int)$connect->lastInsertId();
				log_info('Added word: ' . $add->word . ' to table with id: ' . $add->id);
				return true;
			} catch(PDOException $ex) {
				log_info($ex->getMessage());
				return false;
			}
		}
		
		public function delete(Word $delete) {
			try {
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				
				$command = "DELETE FROM word WHERE word_id = ?";
				
				$stmt = $connect->prepare($command);
				$stmt->bindValue(':id', (int)$delete->id, PDO::PARAM_INT);
				$stmt->execute();
				
				log_info('Deleted word: ' . $delete->language . ' from table with id: ' . $delete->id);
				return true;
			} catch(PDOException $ex) {
				log_info($ex->getMessage());
				return false;
			}
		}

		function add_word_to_category($word_id, $category_id) {
			$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
			if ($connection->connect_errno)
			{
				return false;
			}
			$command = "INSERT INTO is_in VALUES (?, ?)";
	
			if(!($stmt = $connection->prepare($command)))
			{
				echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;
				return false;
			}
			if(!$stmt->bind_param('ii', $word_id, $category_id))
			{
				echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
				return false;
			}
			elseif (!$stmt->execute())
			{
				echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
				return false;
			}
			$connection->close();
			return true;
		}
		
		function delete_word_from_category($word_id, $category_id) {
			$connection = new mysqli(IP, USER, PASSWORD, DATABASE);
			if ($connection->connect_errno)
			{
				return false;
			}
			$command = "DELETE FROM is_in WHERE word_id = ? and category_id = ?";
	
			if(!($stmt = $connection->prepare($command)))
			{
				echo "Prepare failed: (" . $connection->errno . ") " . $connection->error;
				return false;
			}
			if(!$stmt->bind_param('ii', $word_id, $category_id))
			{
				echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
				return false;
			}
			elseif (!$stmt->execute())
			{
				echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
				return false;
			}
			$connection->close();
			return true;
		}
	}
?>
