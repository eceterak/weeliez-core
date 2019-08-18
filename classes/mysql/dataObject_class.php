<?php

	/**
	 * @file dataObject_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class dataObject {

		/**
		 * Array of objects loaded from database or created with create_objects() method.
		 * @param array
		 */

		public $data;

		/**
		 * Amount of objects id $data array. 
		 * Usefull when iterating trough array with for loop.
		 * @param int
		 */

		public $num_rows = 0;

		/**
		 * Navigation panel for pagination. 
		 * It contains a html tags so needs to echo it.
		 * @see pagination_nav()
		 * @param string
		 */

		public $navigation = '';

		/**
		 * Load database into the object.
		 * @param $data [array]
		 * @param $navigation [string]
		 */

		public function __construct($data, $navigation = false) {
			$this->data = $data;
			if($navigation) $this->navigation = $navigation;
			$this->num_rows = count($this->data);
		}

		/**
		 * Return data.
		 * @return array
		 */

		public function fetch_data() {
			return $this->data;
		}

		/**
		 * To save a tons of work and repeating the code, automatically create array of the objects, populate them and replace $this->data with a new array.
		 * Before doing that, check if object of $name exists. 
		 * If not, it's still posible to use $this->data but it will contain raw MySql stdClass objects.
		 * @param $name [string]
		 */

		public function create_objects($name) {
			if(class_exists('\\'.$name)) $name = '\\'.$name;
			if(class_exists($name)) {
				foreach($this->data as $object) {
					$result[] = new $name($object);
				}
				$this->data = $result;
			}
		}

	}

?>