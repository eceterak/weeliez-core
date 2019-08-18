<?php

	/**
	 * @file related_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class related_ extends autoLoad {

		/**
		 * Id of the item.
		 * @var int
		 */

		public $bike_id;

		/**
		 * Name of the item. Required.
		 * @var string
		 */

		public $bike_related_id;

		/**
		 * Bike object.
		 * @var object [bike]
		 */

		public $bike_;

		/**
		 * Load basic bike data and data of all sub items.
		 * @param $result [stdClass]
		 */

		public function cast(stdClass $result) {
			$this->load($result); // Load basic data first, to get bike id.
			$this->bike_ = new bike_($result);
		}
	}
?>