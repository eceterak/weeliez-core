<?php

	/**
	 * @file blog_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class blog_ extends item {

		/**
		 * Blog's id.
		 * @var int
		 */

		public $blog_id;

		/**
		 * Title.
		 * @var string 
		 */
		
		public $blog_title;

		/**
		 * Content can be a pure html. It supports bootstrap an JS.
		 * @var string
		 */
		
		public $blog_content;

		/**
		 * Blog path is a blog title with underscores instead of spaces.
		 * @var string
		 */

		public $blog_path;

		/**
		 * Return id. Required by the interface.
		 * @return int
		 */

		public function getId() {
			return $this->blog_id;
		}

		/**
		 * Create a seo friendly path.
		 * @param $id [int]
		 * @return string/null
		 */

		static public function path($id) {
			$db = new mysqlib();
			$db->where('blog_id', $id);
			$result = $db->selectOne('blog', 'blog_title');
			if($result) {
				$blog = new self($result);
				$path = replaceSpaces($blog->blog_title, '_');
				$db->where('blog_path', $path);
				$db->whereNot('blog_id', $blog->getId()); // Check if any article with the same path already exists.
				$check = $db->checkRecordExists('blog');
				if($check) {
					$path .= '_'.$blog->getId(); // Add id to the path to keep it unique.
				}
				return $path;
			}
			else {
				return null;
			}
		}


		/**
		 * Load and return blog.
		 * @param $path [string]
		 * @return self
		 */

		static public function getByPath($path) {
			$db = new mysqlib();
			$db->where('blog_path', $path);
			$result = $db->selectOne('blog');
			if($result) {
				return new self($result);
			}
			else {
				return false;
			}
		}

		/**
		 * Load and return blog.
		 * @param $id [int] // id of blog to load.
		 * @return self
		 */

		static public function getById($id) {
			$db = new mysqlib();
			$db->where('blog_id', $id);
			$result = $db->selectOne('blog');
			if($result) {
				return new self($result);
			}
			else {
				return false;
			}
		}
	}
?>