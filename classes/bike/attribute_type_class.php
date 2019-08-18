<?php

	/**
	 * @file attribute_type_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class attribute_type_ extends autoLoad {

		/**
		 * Id of the attribute type.
		 * @var int
		 */

		public $attribute_type_id;

		/**
		 * Name of the attribute type
		 * @var string
		 */

		public $attribute_type_name;

		/**
		 * Indicates the position of the attribute type.
		 * @var int
		 */

		public $attribute_type_priority;

		/**
		 * Load and return object. Created to avoid duplication inside of methods, compare data when updating etc.
		 * @param $id [int] // id of object to load.
		 * @return self / bool
		 */

		static public function getById($id) {
			$db = new mysqlib();
			$db->where('attribute_type_id', $id);
			$result = $db->selectOne('attribute_type');
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
			$db->order('attribute_type_priority');
			$result = $db->select('attribute_type', 'attribute_type_id, attribute_type_name, attribute_type_priority');
			$arr = []; // Always return array.
			if($result) {
				foreach($result->fetch_data() as $attribute_type) {
					$arr[] = new self($attribute_type);
				}
			} 			
			return $arr;
		}
	}
?>