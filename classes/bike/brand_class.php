<?php

	/**
	 * @file brand_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class brand_ extends item {

		/**
		 * Id of the brand.
		 * @var int
		 */

		public $brand_id;

		/**
		 * Name of the brand.
		 * @var string
		 */

		public $brand_name;

		/**
		 * Year founded.
		 * @var int
		 */

		public $brand_year = 0;

		/**
		 * Founder.
		 * @var string
		 */

		public $brand_founder = null;

		/**
		 * Headquarters including country.
		 * @var string
		 */

		public $brand_headquarters;

		/**
		 * Description of an brand.
		 * @var string
		 */

		public $brand_description;

		/**
		 * Seo friendly url path.
		 * @var string
		 */

		public $brand_path;

		/**
		 * Amount of bikes associated with brand.
		 * @var int
		 */

		public $bikesAmount = 0;

		/**
		 * Return id. Required by interface.
		 * @return int
		 */

		public function getId() {
			return $this->brand_id;
		}

		/**
		 * Create a new 'empty' object. Use it as first option in select input.
		 * @return self
		 */

		public static function createEmpty() {
			$empty = new self();
			$empty->brand_name = '---';
			$empty->brand_id = 0;
			$empty->brand_year = 0;
			$empty->brand_founder = '';
			$empty->brand_headquarters = '';
			return $empty;
		}

		/**
		 * Connect to db and using count method get amount of bikes associated to current brand.
		 * If there is no bikes in the brand, return 0. 
		 * @return int
		 */

		private function getBikesAmount() {
			$db = new mysqlib();
			$db->where('brand_id', $this->brand_id);
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
			if($id == 0) {
				return self::createEmpty();
			}
			$db = new mysqlib();
			$db->where('brand_id', $id);
			$result = $db->selectOne('brand');
			if($result) {
				return new self($result); // Return instance of new bike with loaded data.
			}
			else {
				return false;
			}
		}

		/**
		 * Load and return object by its name (path).
		 * @param $path [string]
		 * @return self / bool
		 */

		static public function getByPath($path) {
			$db = new mysqlib();
			$db->where('brand_path', $path);
			$result = $db->selectOne('brand');
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
			$result = $db->select('brand', 'brand_id, brand_name');
			$arr = []; // Always return array.
			//$arr[] = self::createEmpty(); // Add an 'empty' brand
			if($result) {
				foreach($result->fetch_data() as $brand) {
					$arr[] = new self($brand);
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
			$db->where('brand_id', $id);
			$result = $db->selectOne('brand', 'brand_name');
			if($result) {
				$brand = new self($result);
				return replaceSpaces($brand->brand_name, '_');
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
				<a href = "<?php echo ADMIN_URL.'/brand/edit/'.$id; ?>">General</a>
				<a href = "<?php echo ADMIN_URL.'/image/brand/'.$id; ?>">Images</a>
			</p>
			<?php
		}
	}
?>