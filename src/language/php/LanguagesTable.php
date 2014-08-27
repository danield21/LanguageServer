<?php
	require_once 'MysqlTable.php';
	require_once 'Language.php';

	class LanguagesTable extends MysqlTable {
		
		public function get_all() {
			try {
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				
				$command = 'SELECT * FROM language;';
				
				$stmt = $connect->prepare($command);
				$stmt->execute();
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				
				$languages = array();
				
				foreach($results as $row){
					$languages[] = new Language($row);
				}
				
				return $languages;
			} catch(PDOException $ex) {
				log_info($ex->getMessage());
				return array();
			}
		}
		
		public function get_by_id($id) {
			$language = new Language;
			if(!isset($id)) {
				return $language;
			}
			try {
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	
				$command = 'SELECT * FROM language WHERE language_id = :id LIMIT 0, 1;';
	
				$stmt = $connect->prepare($command);
				$stmt->bindValue(':id', (int)$id, PDO::PARAM_STR);
				$stmt->execute();
				
				$result = $stmt->fetchAll();
				if(isset($result[0]))
				{
					$language = new Language($result[0]);
				}
			} catch(PDOException $ex) {
				log_info($ex->getMessage());
			}
			return $language;
		}

		public function add(Language $add) {
			try {
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	
				$command = 'INSERT INTO language (language) VALUES (:language);';
	
				$stmt = $connect->prepare($command);
				$stmt->bindValue(':language', $add->language, PDO::PARAM_STR);
				$stmt->execute();
				
				$add->id = (int)$connect->lastInsertId();
				log_info('Added language: ' . $add->language . ' to table with id: ' . $add->id);
				return true;
			} catch(PDOException $ex) {
				log_info($ex->getMessage());
				return false;
			}
		}

		public function edit(Language $edit) {
			try {
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				
				$command = 'UPDATE language SET language= :language WHERE language_id= :id;';
				
				$stmt = $connect->prepare($command);
				$stmt->bindValue(':id', (int)$edit->id, PDO::PARAM_INT);
				$stmt->bindValue(':language', $edit->language, PDO::PARAM_STR);
				$stmt->execute();
				
				log_info("Changed language with id of " . $edit->id . " to " . $edit->language);
				return true;
			} catch(PDOException $ex) {
				log_info($ex->getMessage());
				return false;
			}
		}
		
		public function delete(Language $delete) {
			try {
				$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				
				$command = 'DELETE FROM language WHERE language_id = :id';
				
				$stmt = $connect->prepare($command);
				$stmt->bindValue(':id', (int)$delete->id, PDO::PARAM_INT);
				$stmt->execute();
				
				log_info('Deleted language: ' . $delete->language . ' from table with id: ' . $delete->id);
				return true;
			} catch(PDOException $ex) {
				log_info($ex->getMessage());
				return false;
			}
		}
	}
?>
