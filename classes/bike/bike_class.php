<?php

	/**
	 * @file bike_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class bike_ extends item {

		/**
		 * Id of the item.
		 * @var int
		 */

		public $bike_id;

		/**
		 * Name of the item. Required.
		 * @var string
		 */

		public $bike_name;

		/**
		 * Start of production. Required.
		 * @var int
		 */

		public $bike_year_start;

		/**
		 * Most of the times bikes are produced for many years in same form.
		 * If bike was produced only for a year. Leave year_end empty.
		 * @var int
		 */

		public $bike_year_end = '';

		/**
		 * Bike description.
		 * @var string
		 */

		public $bike_description;

		/**
		 * Is bike available for sale.
		 * @var bool
		 */

		public $bike_sale;

		/**
		 * Brand name + bike name + bike year start combo.
		 * @var string
		 */

		public $bike_path;

		/**
		 * Indicates how many people likes (loves) the bike. It's different from favourites.
		 * @var int
		 */

		public $bike_loves = 0;

		/**
		 * Indicates how many people added bike to their favourites. Not needed in front end.
		 * @var int
		 */

		public $bike_favourites = 0;

		/**
		 * Brand object with all informations about brand.
		 * @var object [brand]
		 */

		public $brand_;

		/**
		 * Category object with all informations about category.
		 * @var object [type]
		 */

		public $category_;

		/**
		 * Array of spec_ objects. Use setSpecs() to populate.
		 * @see setSpecs()
		 * @var array
		 */

		public $specs_ = array();

		/**
		 * Load basic bike data and data of all sub items.
		 * @param $result [stdClass]
		 */

		public function cast(stdClass $result) {
			$this->load($result); // Load basic data first, to get bike id.
			$this->brand_ = new brand_($result);
			$this->category_ = new category_($result);
		}

		/**
		 * Return id. Required by the interface.
		 * @return int
		 */

		public function getId() {
			return $this->bike_id;
		}

		/**
		 * Create a seo friendly path to the bike. When duplicating bike, add it's id to keep it unique.
		 * @param $id [int]
		 * @return string/null
		 */

		static public function path($id) {
			$db = new mysqlib();
			$db->where('bike_id', $id);
			$db->join('brand br', 'b.brand_id = br.brand_id');
			$result = $db->selectOne('bike b', 'b.bike_id, br.brand_name, b.bike_name, b.bike_year_start');
			if($result) {
				$bike = new self($result);
				$path = replaceSpaces($bike->brand_->brand_name.'_'.$bike->bike_name.'_'.$bike->bike_year_start, '_');
				$path = str_replace(str_split('/.?'), '', $path);
				$db->where('bike_path', $path);
				$db->whereNot('bike_id', $bike->getId()); // Exclude current bike.
				$check = $db->checkRecordExists('bike');
				if($check) {
					$path .= '_'.$bike->getId(); // There is a bike with same name and years of production. Add id to the path to keep it unique.
				}
				return $path;
			}
			else {
				return null;
			}
		}

		/**
		 * Load and return bike. Created to avoid duplication inside of methods.
		 * To load default image, set $images to true.
		 * @param $id [int] // id of bike to load.
		 * @param $images [bool]
		 * @return self / bool
		 */

		static public function getById($id, $images = false) {
			$db = new mysqlib();
			$db->join('brand br', 'b.brand_id = br.brand_id'); 
			$db->join('category c', 'b.category_id = c.category_id');
			$db->where('b.bike_id', $id);
			$result = $db->selectOne('bike b');
			if($result) {
				$loves = self::getLove($id, $db); // Check for loves.
				if($loves) {
					$result = (object)array_merge((array)$result, (array)$loves); // Add loves into $result object.
				}
				$favourites = self::getFavourites($result->bike_id, $db); // Get favourites.
				if($favourites) {
					$result = (object) array_merge((array) $result, (array) $favourites);
				}
				$bike = new \bike_($result);
				if($images === true) {
					$bike->images_->loadDefault($id, 'bike'); // Load default image.
				}
				return $bike; // Return instance of new bike with loaded data.
			}
			else {
				return false;
			}
		}

		/**
		 * Load bike by it's the path. Created to support SEO friendly url's.
		 * To load default image, set $images to true.
		 * @param $path [string] // id of bike to load.
		 * @param $images [bool]
		 * @return self / bool
		 */

		static public function getByPath($path, $images = false) {
			$db = new mysqlib();
			$db->join('brand br', 'b.brand_id = br.brand_id'); 
			$db->join('category c', 'b.category_id = c.category_id');
			$db->where('b.bike_path', $path);
			$result = $db->selectOne('bike b');
			if($result) {
				$loves = self::getLove($result->bike_id, $db); // Check for loves.
				if($loves) {
					$result = (object) array_merge((array) $result, (array) $loves);
				}				
				$bike = new \bike_($result);
				if($images === true) {
					$bike->images_->loadDefault($bike); // Load default image.
				}
				return $bike; // Return instance of new bike with loaded data.
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
			$db->join('brand br', 'b.brand_id = br.brand_id');
			$result = $db->select('bike b', 'b.bike_id, b.bike_name, b.bike_year_start, b.bike_year_end, br.brand_name');
			$arr = []; // Always return array.
			if($result) {
				foreach($result->fetch_data() as $bike) {
					$arr[] = new self($bike);
				}
			} 			
			return $arr;
		}

		/**
		 * Use provided data to populate bike's specs. To group attributes by their types, use multidimensional array where first level is a attribute_type id.
		 * Thanks to loadSpecs() all bike data exists within one bike object instead of bike and specs objects.
		 * @param $result [array]
		 */

		public function loadSpecs(dataObject $result = null) {
			if($result) {
				foreach($result->fetch_data() as $res) {
					$this->specs_[$res->attribute_type_priority][$res->attribute_name] = new \spec($res); // '\' To use in both admin and front end.
				}
				ksort($this->specs_); // Sort by atrributeType_priority.
				$attribute_types = attribute_type_::getAll();
				foreach($attribute_types as $attribute_type) {
					if(in_array($attribute_type->attribute_type_priority, array_keys($this->specs_))) {
						$this->specs_[$attribute_type->attribute_type_name] = $this->specs_[$attribute_type->attribute_type_priority]; // Replace type id with name.
						unset($this->specs_[$attribute_type->attribute_type_priority]); // Unset old key and its data.
					}
				}
			}
		}

		/**
		 * Generate a year/s of production of the bike in a format yyyy - yyyy or yyyy.
		 * It also tells if bike was produced for more than one year (use $bool).
		 * @param $bool [bool]
		 * @return bool/string
		 */

		public function getYear($bool = false) {
			if($bool) {
				$year = ($this->bike_year_start != $this->bike_year_end) ? true : false;
			}
			else {
				if($this->bike_sale == 1) {
					$year = $this->bike_year_start.' - ?'; // Bike still on sale.
				}
				elseif($this->bike_year_start == $this->bike_year_end) {
					$year = $this->bike_year_start;	 // Produced for only one year.
				}
				else {
					$year = $this->bike_year_start.' - '.$this->bike_year_end;
				}
			}
			return $year;
		}

		/**
		 * Count how many users loved the bikes.
		 * It must be outside of getById because of where 'bike'.
		 * @param $id [int]
		 * @param $db [mysqlib]
		 * @return stdObject / false
		 */

		static public function getLove($id, mysqlib $db) {
			$db->where('love_item', 'bike');
			$db->where('item_id', $id);
			$loves = $db->selectOne('love', 'COUNT(item_id) as bike_loves');
			if($loves) {
				return $loves;
			}
			else return false;
		}
 
		/**
		 * Count how many users has the bike as a favourite one.
		 * @param $id [int]
		 * @param $db [mysqlib]
		 * @return stdObject / 0
		 */

		static public function getFavourites($id, mysqlib $db) {
			$db->where('bike_id', $id);
			$favourites = $db->selectOne('favourite', 'COUNT(bike_id) as bike_favourites');
			if($favourites) {
				return $favourites;
			}
			else return false;
		}

		/**
		 * Indicates if user loved the bike or not (to display a different heart icon).
		 * @return bool
		 */

		public function checkLove($user) {
			if($user) {
				$db = new mysqlib();
				$db->where('love_item', 'bike');
				$db->where('item_id', $this->bike_id);
				$db->where('user_id', $user->user_id);
				$check = $db->checkRecordExists('love');
				if($check) {
					return true;
				}
			}
			return false;
		}
 
		/**
		 * Indicates if user has bike in his/her favourites.
		 * @return bool
		 */

		public function checkFavourite($user) {
			if($user) {
				$db = new mysqlib();
				$db->where('bike_id', $this->bike_id);
				$db->where('user_id', $user->user_id);
				$check = $db->checkRecordExists('favourite');
				if($check) {
					return true;
				}
			}
			return false;
		}
		/**
		 * Temporary method.
		 * @todo do not use in the final version.
		 */

		public static function menu($id) {
			?>
			<ul class = "nav nav-pills mb-3">
				<li class = "nav-item"><a href = "<?php echo ADMIN_URL.'/bike/edit/'.$id; ?>">General</a></li>
				<li class = "nav-item"><span class = "img-manage icon-click">Images</span></li>
				<li class = "nav-item"><a href = "<?php echo ADMIN_URL.'/bike/specs/'.$id; ?>">Specs</a></li>
				<li class = "nav-item"><a href = "<?php echo ADMIN_URL.'/bike/related/'.$id; ?>">Related</a></li>
			</ul>
			<?php
		}

		/**
		 * Display the navigation/breadcrumbs.
		 * Navigation differs from item to another, that's why this method is needed.
		 */

		public function breadcrumbs() {
			return '<a>'.$this->brand_->brand_name.'</a> / '.'<a>'.$this->bike_name.'</a>';
		}
	}
?>