<?php

	/**
	 * @file article_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class article_ extends item {

		/**
		 * Item's id.
		 * @var int
		 */

		public $article_id;

		/**
		 * Title of the article. It replaces name property.
		 * @var string
		 */

		public $article_title;

		/**
		 * Content of the article.
		 * @var string
		 */

		public $article_content = '';

		/**
		 * Article date.
		 * @var int
		 */

		public $article_date;

		/**
		 * Indicates if article's content is validated by the admin/moderator.
		 * @var int
		 */

		public $article_valid;

		/**
		 * Article path is a article title with underscores instead of spaces.
		 * @var string
		 */

		public $article_path;

		/**
		 * User object with all informations about user.
		 * @var object [user]
		 */

		public $user_;

		/**
		 * Category of article.
		 * @var object [article_category_]
		 */

		public $article_category_;

		/**
		 * Load article and user data.
		 * @param $result [stdClass]
		 */

		public function cast($result) {
			$this->load($result); // Load basic data first, to get bike id.
			$this->user_ = new user_($result);
			$this->article_category_ = new article_category_($result);
		}

		/**
		 * Return id. Required by the interface.
		 * @return int
		 */

		public function getId() {
			return $this->article_id;
		}

		/**
		 * Create a seo friendly path to the article.
		 * @param $id [int]
		 * @return string/null
		 */

		static public function path($id) {
			$db = new mysqlib();
			$db->where('article_id', $id);
			$result = $db->selectOne('article', 'article_title');
			if($result) {
				$article = new self($result);
				$path = replaceSpaces($article->article_title, '_');
				$db->where('article_path', $path);
				$db->whereNot('article_id', $article->getId()); // Check if any article with the same path already exists.
				$check = $db->checkRecordExists('article');
				if($check) {
					$path .= '_'.$article->getId(); // Add id to the path to keep it unique.
				}
				return $path;
			}
			else {
				return null;
			}
		}

		/**
		 * Load and return article. Created to avoid duplication inside of methods.
		 * @param $id [int] // id of bike to load.
		 * @return self
		 */

		static public function getById($id) {
			$db = new mysqlib();
			$db->join('user u', 'u.user_id = art.user_id');
			$db->join('article_category c', 'c.article_category_id = art.article_category_id');
			$db->where('art.article_id', $id);
			$result = $db->selectOne('article art');
			if($result) {
				return new self($result);
			}
			else {
				return false;
			}
		}

		/**
		 * Get article by its path.
		 * @param $path [string]
		 * @return self
		 */

		static public function getByPath($path) {
			$db = new mysqlib();
			$db->join('user u', 'u.user_id = art.user_id');
			$db->join('article_category c', 'c.article_category_id = art.article_category_id');
			$db->where('art.article_path', $path);
			$result = $db->selectOne('article art');
			if($result) {
				return new self($result);
			}
			else {
				return false;
			}
		}

		/**
		 * Make MySQL timestamp more readable by using dateTime->format().
		 * If no time needed, set $time to false.
		 * @param $time [bool]
		 * @return [string]
		 */

		public function formatDate($time = true) {
			if(!empty($this->article_date)) {
				$date = new dateTime($this->article_date);
				return ($time == true) ? $date->format('M jS, Y - H:i') : $date->format('M jS, Y');
			}
		}

		/**
		 * Crop article content to fit on the home page etc.
		 * @param $lenght [int]
		 * @return string
		 */

		public function contentShort($length = 20) {
			if($this->article_content !== '') {
				$this->article_content = strip_tags($this->article_content);
				$contentLength = strlen($this->article_content);
				$content = substr($this->article_content, '0', $length);
				if($contentLength > $length) $content .= '...';
				return $content;
			}
		}
	}

?>