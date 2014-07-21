<?php
require_once 'Category.php';

class CategoriesTable {
	private $ip_;
	private $user_;
	private $pass_;
	private $db_;
	
	public function __construct($ip, $user, $pass, $db) {
		$this->ip_ = $ip;
		$this->user_ = $user;
		$this->pass_ = $pass;
		$this->db_ = $db;
	}

	public function get_all() {
		try {
			$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			
			$command = 'SELECT * FROM category;';
			
			$stmt = $connect->prepare($command);
			$stmt->execute();
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
			$categories = array();
			
			foreach($results as $row){
				$categories[] = new Category($row);
			}
		
			return $categories;
		} catch(PDOException $ex) {
			log_info($ex->getMessage());
			return array();
		}
	}

	public function get_by_id($id) {
		$category = new Category;
		if(!isset($id)) {
			return $category;
		}
		try {
			$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	
			$command = 'SELECT * FROM category WHERE category_id = :id LIMIT 0, 1;';
	
			$stmt = $connect->prepare($command);
			$stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
			$stmt->execute();
	
			
			$result = $stmt->fetchAll();
			if(isset($result[0]))
			{
				$category = new Category($result[0]);
			}
			
			return $category;
		} catch(PDOException $ex) {
			log_info($ex->getMessage());
			return $category;
		}
	}

	public function get_by_word(Word $word) {
		$categories = array();
		try {
			$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	
			$command = 'SELECT * FROM is_in WHERE word_id = ?;';
	
			$stmt = $connect->prepare($command);
			$stmt->bindValue(':id', (int)$word->id, PDO::PARAM_INT);
			$stmt->execute();
			
			foreach($results as $row){
				$categories[] = new Category($row);
			}
		} catch(PDOException $ex) {
			log_info($ex->getMessage());
		}
		return $categories;
	}
	
	public function get_direct_children(Category $current) {
		$categories = array();
		if(!isset($current) || !$current->is_valid()) {
			return $categories;
		}
		try {
			$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			
			$command = 'SELECT ' .
					'category.category_id AS category_id' .
					', category.category AS category ' .
					', category.description AS description' .
						' FROM category, is_subcategory_of' .
							' WHERE category.category_id = is_subcategory_of.subcategory_id' .
							' AND is_subcategory_of.parentcategory_id = :id;';
			
			$stmt = $connect->prepare($command);
			$stmt->bindValue(':id', (int)$current->id, PDO::PARAM_INT);
			$stmt->execute();
			$results = $stmt->fetchAll();
			
			foreach($results as $row){
				$categories[] = new Category($row);
			}
		} catch(PDOException $ex) {
			log_info($ex->getMessage());
		}
		return $categories;
	}

	public function get_all_children(Category $current) {
		$categories = $this->get_direct_children($current);
		$count = count($categories);
		for($i = 0; $i < $count; ++$i) {
			$subcategories = $this->get_direct_children($categories[$i]);
			foreach($subcategories as $subcat)
			{
				$repeat = false;
				foreach($categories as $cat) {
					if($cat->equals($subcat)) {
						$repeat = true;
						break;
					}
				}
				if(!$repeat) {
					$categories[] = $subcat;
					++$count;
				}
			}
		}
		return $categories;
	}

	public function get_direct_parents(Category $current) {
		$categories = array();
		if(!isset($current) || !$current->is_valid()) {
			return $categories;
		}
		try {
			$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			
			
			$command = 'SELECT ' .
					'category.category_id AS category_id' .
					', category.category AS category ' .
					', category.description AS description' .
						' FROM category, is_subcategory_of' .
							' WHERE category.category_id = is_subcategory_of.parentcategory_id' .
							' AND is_subcategory_of.subcategory_id = :id;';
			
			$stmt = $connect->prepare($command);
			$stmt->bindValue(':id', (int)$current->id, PDO::PARAM_INT);
			$stmt->execute();
			$results = $stmt->fetchAll();
			
			foreach($results as $row){
				$categories[] = new Category($row);
			}
		} catch(PDOException $ex) {
			log_info($ex->getMessage());
		}
		return $categories;
	}

	public function get_all_parents(Category $current) {
		$categories = $this->get_direct_parents($current);
		$count = count($categories);
		for($i = 0; $i < $count; ++$i) {
			$subcategories = $this->get_direct_parents($categories[$i]);
			foreach($subcategories as $subcat)
			{
				$repeat = false;
				foreach($categories as $cat) {
					if($cat->equals($subcat)) {
						$repeat = true;
						break;
					}
				}
				if(!$repeat) {
					$categories[] = $subcat;
					++$count;
				}
			}
		}
		return $categories;
	}
	
	public function add(Category $add) {
		try {
			$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

			$command = 'INSERT INTO category (category) VALUES (:category);';

			$stmt = $connect->prepare($command);
			$stmt->bindValue(':category', $add->language, PDO::PARAM_STR);
			$stmt->execute();
			
			$add->id = (int)$connect->lastInsertId();
			log_info('Added category: ' . $add->language . ' to table with id: ' . $add->id);
			return true;
		} catch(PDOException $ex) {
			log_info($ex->getMessage());
			return false;
		}
	}

	public function edit(Category $edit) {
		try {
			$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			
			$command = 'UPDATE category SET category= :category WHERE category_id= :id;';
			
			$stmt = $connect->prepare($command);
			$stmt->bindValue(':id', (int)$edit->id, PDO::PARAM_INT);
			$stmt->bindValue(':category', $edit->language, PDO::PARAM_STR);
			$stmt->execute();
			
			log_info("Changed category with id of " . $id . " to " . $language);
			return true;
		} catch(PDOException $ex) {
			log_info($ex->getMessage());
			return false;
		}
	}
	
	public function delete(Category $delete) {
		try {
			$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			
			$command = 'DELETE FROM category WHERE category_id = :id';
			
			$stmt = $connect->prepare($command);
			$stmt->bindValue(':id', (int)$delete->id, PDO::PARAM_INT);
			$stmt->execute();
			
			log_info('Deleted category: ' . $delete->category . ' from table with id: ' . $delete->id);
			return true;
		} catch(PDOException $ex) {
			log_info($ex->getMessage());
			return false;
		}
	}

	public function add_relationship(Category $child, Category $parent) {
		try {
			$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			
			$command = 'INSERT INTO is_subcategory_of (subcategory_id, parentcategory_id) VALUES (:childID, :parentID)';
			
			$stmt = $connect->prepare($command);
			$stmt->bindValue(':childID', (int)$child->id, PDO::PARAM_INT);
			$stmt->bindValue(':parentID', (int)$parent->id, PDO::PARAM_INT);
			$stmt->execute();
			
			log_info('Added relationship with ' . $parent->category . ' parenting ' . $child->category);
			return true;
		} catch(PDOException $ex) {
			log_info($ex->getMessage());
			return false;
		}
	}

	public function delete_relationship(Category $child, Category $parent) {
		try {
			$connect = new PDO('mysql:host=' . $this->ip_ . '; dbname=' . $this->db_ . '; charset=utf8', $this->user_, $this->pass_);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			
			$command = 'INSERT INTO is_subcategory_of (subcategory_id, parentcategory_id) VALUES (:childID, :parentID)';
			
			$stmt = $connect->prepare($command);
			$stmt->bindValue(':childID', (int)$child->id, PDO::PARAM_INT);
			$stmt->bindValue(':parentID', (int)$parent->id, PDO::PARAM_INT);
			$stmt->execute();
			
			log_info('Deleted relationship of ' . $parent->category . ' parenting ' . $child->category);
			return true;
		} catch(PDOException $ex) {
			log_info($ex->getMessage());
			return false;
		}
	}
}
?>
