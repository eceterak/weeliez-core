<?php

	/**
	 * @file article_category_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class article_category_ extends autoLoad {

		/**
		 * Id of the item.
		 * @var int
		 */

		public $article_category_id;

		/**
		 * Name of category (including news category).
		 * @var int
		 */

		public $article_category_name;

		/**
		 * Indicates if category is active or not. Articles of inactive (0) category, won't be displayed.
		 * @var int
		 */

		public $article_category_active;

		/**
		 * Path to the category.
		 * @var string
		 */

		public $article_category_path;

		/**
		 * Return id. Required by the interface.
		 * @return int
		 */

		public function getId() {
			return $this->article_category_id;
		}

		/**
		 * Load and return item. Created to avoid duplication inside of methods.
		 * @param $id [int] // id of item to load.
		 * @return self
		 */

		static public function getById($id) {
			$db = new mysqlib();
			$db->where('article_category_id', $id);
			$result = $db->selectOne('article_category');
			if($result) {
				return new self($result);
			}
			else {
				return false;
			}
		}

		/**
		 * Get path.
		 * @param $path [string]
		 * @return self
		 */

		static public function getByPath($path) {
			$db = new mysqlib();
			$db->where('article_category_path', $path);
			$result = $db->selectOne('article_category');
			if($result) {
				return new self($result);
			}
			else {
				return false;
			}
		}

		/**
		 * Static function to return all items. Usefull when displaying data in select input.
		 * @return array
		 */

		static public function getAll() {	
			$db = new mysqlib();
			$result = $db->select('article_category');
			$arr = []; // Always return array.
			if($result) {
				foreach($result->fetch_data() as $category) {
					$arr[] = new self($category);
				}
			} 			
			return $arr;
		}

		/**
		 * Create a seo friendly path.
		 * @param $id [int]
		 * @return string/null
		 */

		static public function path($id) {
			$db = new mysqlib();
			$db->where('article_category_id', $id);
			$result = $db->selectOne('article_category', 'article_category_name');
			if($result) {
				$category = new self($result);
				$path = replaceSpaces($category->article_category_name, '_');
				$db->where('article_category_path', $path);
				$db->whereNot('article_category_id', $category->getId()); // Check if any article with the same path already exists.
				$check = $db->checkRecordExists('article_category');
				if($check) {
					$path .= '_'.$article->getId(); // Add id to the path to keep it unique.
				}
				return $path;
			}
			else {
				return null;
			}
		}
	}

?>