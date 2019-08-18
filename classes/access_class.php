<?php

	/**
	 * @file access_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class access_ extends autoLoad {

		/**
		 * Id of the item.
		 * @var int
		 */

		public $access_id;

		/**
		 * Name of access.
		 * @var int
		 */

		public $access_name;

		/**
		 * Higher access level means more capibilities for user.
		 * @var int
		 */

		public $access_level;

		/**
		 * Load and return item. Created to avoid duplication inside of methods.
		 * @param $id [int] // id of item to load.
		 * @return self
		 */

		static public function getById($id) {
			$db = new mysqlib();
			$db->where('access_id', $id);
			$result = $db->selectOne('access');
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
			$result = $db->select('access', 'access_id, access_name');
			$arr = []; // Always return array.
			if($result) {
				foreach($result->fetch_data() as $access) {
					$arr[] = new self($access);
				}
			} 			
			return $arr;
		}
	}

?>