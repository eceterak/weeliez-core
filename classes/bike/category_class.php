<?php

	/**
	 * @file category_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class category_ extends item {

		/**
		 * Id of the item.
		 * @var int
		 */

		public $category_id;

		/**
		 * Name of the item.
		 * Set default name so 
		 * @var string
		 */

		public $category_name;

		/**
		 * Description of an item.
		 * @var string
		 */
		
		public $category_description;

		/**
		 * Seo friendly url path.
		 * @var string
		 */

		public $category_path;

		/**
		 * Amount of bikes associated with category.
		 * @var int
		 */

		public $bikesAmount = 0;

		/**
		 * Return id. Required by the interface.
		 * @return int
		 */

		public function getId() {
			return $this->category_id;
		}

		/**
		 * Create a new 'empty' object. Use it as first option in select input.
		 */

		public static function createEmpty() {
			$empty = new self();
			$empty->category_name = '---';
			$empty->category_id = 0;
			return $empty;
		}

		/**
		 * Connect to db and using count method get amount of bikes associated to current type.
		 * If there is no bikes in the brand, return 0. 
		 * @return int
		 */

		private function getBikesAmount() {
			$db = new mysqlib();
			$db->where('category_id', $this->category_id);
			$result = $db->count('bike');
			if($result) return $result;		
			else return 0; // Show 0 on front end.
		}

		/**
		 * Load and return object. Created to avoid duplication inside of methods, compare data when updating etc.
		 * @param $id [int] // id of object to load.
		 * @return self / bool
		 */

		static public function getById($id) {
			$db = new mysqlib();
			$db->where('category_id', $id);
			$result = $db->selectOne('category');
			if($result) {
				return new self($result); // Return instance of new bike with loaded data.
			}
			else {
				return false;
			}
		}

		/**
		 * Load and return object by its path.
		 * @param $path [string]
		 * @return self / bool
		 */

		static public function getByPath($path) {
			$db = new mysqlib();
			$db->where('category_path', $path);
			$result = $db->selectOne('category');
			if($result) {
				return new self($result); // Return instance of new bike with loaded data.
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
			$result = $db->select('category', 'category_id, category_name');
			$arr = []; // Always return array.
			//$arr[] = self::createEmpty(); // Add an 'empty' category.
			if($result) {
				foreach($result->fetch_data() as $category) {
					$arr[] = new self($category);
				}
			} 			
			return $arr;
		}

		/**
		 * Create a seo friendly path to the item.
		 * @param $id [int]
		 * @return string/null
		 */

		static public function path($id) {
			$db = new mysqlib();
			$db->where('category_id', $id);
			$result = $db->selectOne('category', 'category_name');
			if($result) {
				$category = new self($result);
				return replaceSpaces($category->category_name, '_');
			}
			else {
				return null;
			}
		}

		/**
		 * Temporary method.
		 * @todo do not use in a final version.
		 */

		public static function menu($id) {
			?>
			<p>
				<a href = "<?php echo ADMIN_URL.'/type/edit/'.$id; ?>">General</a>
				<a href = "<?php echo ADMIN_URL.'/image/type/'.$id; ?>">Images</a>
			</p>
			<?php
		}
	}
?>